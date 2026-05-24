<?php

namespace App\Mail;

use App\Models\Applicant;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ManualPaymentVerified extends Mailable
{
    use Queueable, SerializesModels;

    public Applicant $applicant;
    public Payment $payment;
    public string $emailType;

    public function __construct(Applicant $applicant, Payment $payment, string $emailType = 'applicant')
    {
        $this->applicant = $applicant;
        $this->payment = $payment;
        $this->emailType = $emailType;
    }

    public function build()
    {
        $subject = $this->emailType === 'admin'
            ? 'Manual Payment Verified'
            : 'Payment Verified Successfully';

        $view = $this->emailType === 'admin'
            ? 'emails.manual_payment_verified_admin'
            : 'emails.manual_payment_verified_applicant';

        return $this->subject($subject)
            ->view($view)
            ->with([
                'applicant' => $this->applicant,
                'payment' => $this->payment,
            ]);
    }
}