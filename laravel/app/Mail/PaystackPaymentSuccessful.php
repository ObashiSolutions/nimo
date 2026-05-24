<?php

namespace App\Mail;

use App\Models\Applicant;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaystackPaymentSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public Applicant $applicant;
    public Payment $payment;
    public string $emailType;

    public function __construct(Applicant $applicant, Payment $payment, string $emailType = 'admin')
    {
        $this->applicant = $applicant;
        $this->payment = $payment;
        $this->emailType = $emailType;
    }

    public function build()
    {
        $subject = $this->emailType === 'applicant'
            ? 'Payment Received Successfully'
            : 'Paystack Payment Verified';

        $view = $this->emailType === 'applicant'
            ? 'emails.paystack_payment_success_applicant'
            : 'emails.paystack_payment_success_admin';

        return $this->subject($subject)
            ->view($view)
            ->with([
                'applicant' => $this->applicant,
                'payment' => $this->payment,
            ]);
    }
}