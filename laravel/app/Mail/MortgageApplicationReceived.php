<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class MortgageApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    protected array $attachmentFiles;
    protected string $emailType;

    public function __construct(array $data, array $attachmentFiles = [], string $emailType = 'admin')
    {
        $this->data = $data;
        $this->attachmentFiles = $attachmentFiles;
        $this->emailType = $emailType;
    }

    public function build()
    {
        $subject = $this->emailType === 'applicant'
            ? 'Copy of Mortgage Pre-Approval Application'
            : 'New Mortgage Pre-Approval Application';

        $view = $this->emailType === 'applicant'
            ? 'emails.mortgage_application_copy'
            : 'emails.mortgage_application';

        $email = $this->subject($subject)
            ->view($view)
            ->with(['data' => $this->data]);

        foreach ($this->attachmentFiles as $file) {
            if (Storage::disk('local')->exists($file)) {
                $email->attach(
                    Storage::disk('local')->path($file)
                );
            }
        }

        return $email;
    }
}