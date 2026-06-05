<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Mail\PaystackPaymentSuccessful;
use Illuminate\Support\Facades\Mail;





class PaystackController extends Controller
{
    public function initialize(Applicant $applicant)
    {
        $amount = (int) env('PAYSTACK_PAYMENT_AMOUNT', 20000000);
        $reference = 'NM-' . $applicant->id . '-' . strtoupper(Str::random(10));

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $applicant->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('paystack.callback'),
                'metadata' => [
                    'applicant_id' => $applicant->id,
                    'reference_id' => $applicant->reference_id,
                    'name' => $applicant->first_name . ' ' . $applicant->last_name,
                ],
            ]);

        if (!$response->successful() || !$response->json('status')) {
            return back()->withErrors([
                'payment' => 'Unable to start Paystack payment. Please try again or upload manual receipt.',
            ]);
        }

        Payment::create([
            'applicant_id' => $applicant->id,
            'provider' => 'paystack',
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'status' => 'initialized',
            'authorization_url' => $response->json('data.authorization_url'),
            'provider_response' => $response->json(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'payment_initialized',
            'message' => 'Paystack payment initialized.',
            'performed_by' => 'Applicant',
        ]);
        
        return redirect($response->json('data.authorization_url'));
    }

    

    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()
                ->route('application.success')
                ->withErrors(['payment' => 'Missing payment reference.']);
        }

        $payment = Payment::where('reference', $reference)->firstOrFail();

        $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (!$response->successful() || !$response->json('status')) {
            $payment->update([
                'status' => 'verification_failed',
                'provider_response' => $response->json(),
            ]);

            return redirect()
                ->route('application.payment', $payment->applicant_id)
                ->withErrors(['payment' => 'Payment verification failed. Please try again or upload manual receipt.']);
        }

        $paystackStatus = $response->json('data.status');
        $paidAmount = (int) $response->json('data.amount');
        $expectedAmount = (int) $payment->amount;

        if ($paystackStatus === 'success' && $paidAmount === $expectedAmount) {
            $payment->update([
                'status' => 'success',
                'provider_response' => $response->json(),
            ]);

            $payment->applicant->update([
                'payment_status' => 'Paid',
                'application_status' => 'Payment Verified',
                'payment_submitted_at' => now(),
            ]);

            ApplicantTimeline::create([
                'applicant_id' => $payment->applicant_id,
                'event_type' => 'payment_verified',
                'message' => 'Paystack payment verified successfully.',
                'performed_by' => 'Paystack',
            ]);

            // SEND EMAILS HERE
            Mail::to(config('mail.admin_address'))
                ->send(new PaystackPaymentSuccessful(
                    $payment->applicant,
                    $payment,
                    'admin'
                ));

            Mail::to($payment->applicant->email)
                ->send(new PaystackPaymentSuccessful(
                    $payment->applicant,
                    $payment,
                    'applicant'
                ));

            return redirect()
                ->route('application.success')
                ->with('success_type', 'online_payment');
        }

        $payment->update([
            'status' => $paystackStatus ?? 'failed',
            'provider_response' => $response->json(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $payment->applicant_id,
            'event_type' => 'payment_failed',
            'message' => 'Paystack payment was not successful.',
            'performed_by' => 'Paystack',
        ]);

        return redirect()
            ->route('application.payment', $payment->applicant_id)
            ->withErrors(['payment' => 'Payment was not successful. Please try again or upload manual receipt.']);
    }

    public function webhook(Request $request)
    {
        $secret = env('PAYSTACK_SECRET_KEY');

        $signature = $request->header('x-paystack-signature');

        $computedSignature = hash_hmac(
            'sha512',
            $request->getContent(),
            $secret
        );

        if (!$signature || $signature !== $computedSignature) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 401);
        }

        $payload = $request->all();

        if (($payload['event'] ?? null) !== 'charge.success') {
            return response()->json([
                'message' => 'Event ignored',
            ]);
        }

        $reference = $payload['data']['reference'] ?? null;

        if (!$reference) {
            return response()->json([
                'message' => 'No reference found',
            ], 400);
        }

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found',
            ], 404);
        }

        if ($payment->status === 'success') {
            return response()->json([
                'message' => 'Payment already verified',
            ]);
        }

        $paidAmount = (int) ($payload['data']['amount'] ?? 0);

        if ($paidAmount !== (int) $payment->amount) {
            $payment->update([
                'status' => 'amount_mismatch',
                'provider_response' => $payload,
            ]);

            return response()->json([
                'message' => 'Amount mismatch',
            ], 400);
        }

        $payment->update([
            'status' => 'success',
            'provider_response' => $payload,
        ]);

        $payment->applicant->update([
            'payment_status' => 'Paid',
            'application_status' => 'Payment Verified',
            'payment_submitted_at' => now(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $payment->applicant_id,
            'event_type' => 'paystack_webhook_verified',
            'message' => 'Paystack payment verified by webhook.',
            'performed_by' => 'Paystack',
        ]);

        return response()->json([
            'message' => 'Payment verified successfully',
        ]);
    }
}
