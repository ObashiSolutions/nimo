<?php

namespace App\Mail;

use App\Models\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PaymentReceiptSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Applicant $applicant;

    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    public function build()
    {
        $email = $this
            ->subject('Mortgage Payment Receipt Submitted')
            ->view('emails.payment_receipt_submitted')
            ->with([
                'applicant' => $this->applicant,
            ]);

        if (
            $this->applicant->receipt_path
            &&
            Storage::disk('local')->exists($this->applicant->receipt_path)
        ) {

            $email->attach(
                Storage::disk('local')->path($this->applicant->receipt_path)
            );
        }

        return $email;
    }
}