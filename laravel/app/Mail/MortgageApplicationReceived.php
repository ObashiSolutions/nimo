<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MortgageApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    // Data used inside the email view
    public $data;

    /**
     * Paths under storage/app, e.g. ['private_applicants_data/file1.pdf', ...]
     */
    protected array $attachmentFiles;

    /**
     * @param array $data            Data for the email body
     * @param array $attachmentFiles Relative paths under storage/app
     */
    public function __construct(array $data, array $attachmentFiles = [])
    {
        $this->data = $data;
        $this->attachmentFiles = $attachmentFiles;
    }

    public function build()
    {
        // Keep your original subject + view
        $email = $this->subject('New pre-approval mortgage application submitted.')
                      ->view('emails.mortgage_application')
                      ->with(['data' => $this->data]);

        // Minimal + safe attachment fix
        foreach ($this->attachmentFiles as $file) {
            $fullPath = storage_path('app/' . $file);

            if (file_exists($fullPath)) {
                $email->attach($fullPath);
            }
        }

        return $email;
    }
}
