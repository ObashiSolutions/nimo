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
}