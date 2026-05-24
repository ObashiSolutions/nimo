<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlutterwaveController extends Controller
{
    public function initialize(Applicant $applicant)
    {
        $amount = env('PAYSTACK_PAYMENT_AMOUNT') / 100;

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => uniqid('FLW_'),
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

        $paymentLink = $response['data']['link'] ?? null;

        if (!$paymentLink) {
            return back()->withErrors('Unable to initialize Flutterwave payment.');
        }

        return redirect($paymentLink);
    }

    public function callback(Request $request)
    {
        return redirect()->route('application.success');
    }

    public function webhook(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}