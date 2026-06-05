<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PaystackPaymentSuccessful;

class FlutterwaveController extends Controller
{
    public function initialize(Applicant $applicant)
    {
        $amount = ((int) env('PAYSTACK_PAYMENT_AMOUNT', 20000000)) / 100;

        $reference = 'FLW-' . $applicant->id . '-' . strtoupper(Str::random(10));

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $reference,
                'amount' => $amount,
                'currency' => 'NGN',
                'redirect_url' => route('flutterwave.callback'),

                'customer' => [
                    'email' => $applicant->email,
                    'name' => $applicant->first_name . ' ' . $applicant->last_name,
                ],

                'customizations' => [
                    'title' => 'Nigeria Mortgages',
                    'description' => 'Mortgage Pre-Approval Payment',
                ],
            ]);

        if (!$response->successful()) {
            return back()->withErrors([
                'payment' => 'Unable to start Flutterwave payment.',
            ]);
        }

        Payment::create([
            'applicant_id' => $applicant->id,
            'provider' => 'flutterwave',
            'reference' => $reference,
            'amount' => (int) env('PAYSTACK_PAYMENT_AMOUNT', 20000000),
            'currency' => 'NGN',
            'status' => 'initialized',
            'authorization_url' => $response->json('data.link'),
            'provider_response' => $response->json(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'payment_initialized',
            'message' => 'Flutterwave payment initialized.',
            'performed_by' => 'Applicant',
        ]);

        return redirect($response->json('data.link'));
    }

    // This callback method will be called by Flutterwave after the payment process is completed
    public function callback(Request $request)
    {
        $transactionId = $request->query('transaction_id');

        if (!$transactionId) {
            return redirect()
                ->route('application.success')
                ->withErrors(['payment' => 'Missing Flutterwave transaction ID.']);
        }

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        if (!$response->successful()) {
            return redirect()
                ->route('application.success')
                ->withErrors(['payment' => 'Flutterwave verification failed.']);
        }

        $data = $response->json('data');

        $reference = $data['tx_ref'] ?? null;

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()
                ->route('application.success')
                ->withErrors(['payment' => 'Payment record not found.']);
        }

        if ($payment->status === 'success') {
            return redirect()
                ->route('application.success')
                ->with('success_type', 'online_payment');
        }

        $expectedAmount = $payment->amount / 100;
        $paidAmount = (float) ($data['amount'] ?? 0);

        if (
            ($data['status'] ?? null) === 'successful'
            && $paidAmount == $expectedAmount
        ) {

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
                'message' => 'Flutterwave payment verified successfully.',
                'performed_by' => 'Flutterwave',
            ]);

            Mail::to(config('mail.admin_address'))
                ->send(new FlutterwavePaymentSuccessful(
                    $payment->applicant,
                    $payment,
                    'admin'
                ));

            Mail::to($payment->applicant->email)
                ->send(new FlutterwavePaymentSuccessful(
                    $payment->applicant,
                    $payment,
                    'applicant'
                ));

            return redirect()
                ->route('application.success')
                ->with('success_type', 'online_payment');
        }

        $payment->update([
            'status' => 'failed',
            'provider_response' => $response->json(),
        ]);

        return redirect()
            ->route('application.payment', $payment->applicant_id)
            ->withErrors([
                'payment' => 'Flutterwave payment was not successful.'
            ]);
    }


    // This webhook method will be called by Flutterwave to notify about payment status changes
    public function webhook(Request $request)
    {
        $hash = $request->header('verif-hash');

        if ($hash !== env('FLW_WEBHOOK_SECRET_HASH')) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 401);
        }

        $payload = $request->all();

        $reference = data_get($payload, 'data.tx_ref');

        if (!$reference) {
            return response()->json([
                'message' => 'Missing reference'
            ], 400);
        }

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return response()->json([
                'message' => 'Payment not found'
            ], 404);
        }

        if ($payment->status === 'success') {
            return response()->json([
                'message' => 'Already verified'
            ]);
        }

        $amount = (float) data_get($payload, 'data.amount', 0);
        $expectedAmount = $payment->amount / 100;

        if (
            data_get($payload, 'data.status') !== 'successful'
            || $amount != $expectedAmount
        ) {
            return response()->json([
                'message' => 'Verification failed'
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
            'event_type' => 'flutterwave_webhook_verified',
            'message' => 'Flutterwave payment verified by webhook.',
            'performed_by' => 'Flutterwave',
        ]);

        return response()->json([
            'message' => 'Verified'
        ]);
    }
}
