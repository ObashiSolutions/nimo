<?php

namespace App\Http\Controllers;

use App\Models\ApplicantTimeline;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Mail\ManualPaymentVerified;
use Illuminate\Support\Facades\Mail;



class StaffPaymentController extends Controller
{
    public function verifyManualPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'success',
        ]);

        $payment->applicant->update([
            'payment_status' => 'Paid',
            'application_status' => 'Payment Verified',
            'payment_submitted_at' => now(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $payment->applicant_id,
            'event_type' => 'manual_payment_verified',
            'message' => 'Manual payment receipt verified by staff.',
            'performed_by' => Auth::guard('staff')->user()?->first_name,
        ]);

        Mail::to($payment->applicant->email)
            ->send(new ManualPaymentVerified(
                $payment->applicant,
                $payment,
                'applicant'
            ));

        Mail::to(config('mail.admin_address'))
            ->send(new ManualPaymentVerified(
                $payment->applicant,
                $payment,
                'admin'
            ));
        return back()->with('success_message', 'Manual payment verified successfully.');
    }
}
