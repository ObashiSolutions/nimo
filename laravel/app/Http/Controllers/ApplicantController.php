<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MortgageApplicationReceived;
use App\Models\ApplicantDocument;
use App\Mail\PaymentReceiptSubmitted;
use App\Models\ApplicantTimeline;




class ApplicantController extends Controller
{
    /**
     * Show the main one-page mortgage pre-approval form.
     */
    public function showForm()
    {
        return view('application');
    }

    /**
     * Handle the submission of the application form and save the data.
     */
    public function submitApplication(Request $request)
    {
        // ============================================================
        // STEP 1: VALIDATION RULES (MATCHING THE CURRENT FORM)
        // ============================================================

        $rules = [
            // 1. PERSONAL DATA
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'city'         => 'nullable|string|max:255',
            'state'        => 'nullable|string|max:255',
            

            // email must look like an email address
            'email'        => 'required|email:rfc,dns|max:255',

            // phone: allow +, spaces, brackets etc. (so not digits_only any more)
            'phone_number' => 'required|string|max:30',

            // 2. WORK INFORMATION
            'company_name'   => 'nullable|string|max:255',
            'years_employed' => 'nullable|integer|min:0',
            'occupation'     => 'nullable|string|max:255',
            'title'          => 'nullable|string|max:255',

            // 3. PROPERTY INFORMATION
            'agent_name'        => 'nullable|string|max:255',
            'estate_name'       => 'nullable|string|max:255',
            'property_address'  => 'nullable|string|max:255',
            'property_cost'     => 'nullable|numeric|min:25000000',

            // 4. reCAPTCHA
            'g-recaptcha-response' => 'required|captcha',

            // 5. SUPPORTING DOCUMENTS (single input, many files)
            'supporting_documents.*' => 'nullable|mimes:pdf,jpg,jpeg,png,zip,rar|max:32768', // up to ~32MB each
        ];

        $messages = [
            'email.unique'  => 'The provided email has already submitted an application. Please contact us via WhatsApp if you need to update your details.',
            'property_cost.min' => 'The property cost must be a realistic value.',
            'property_cost.numeric' => 'The property cost must be a number (no commas or symbols).',
            'supporting_documents.*.mimes' => 'Each supporting document must be a PDF, JPG, JPEG, PNG, ZIP, or RAR file.',
            'supporting_documents.*.max'   => 'Each supporting document must not be larger than 32MB.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA to verify you are human.',
            'g-recaptcha-response.captcha'  => 'reCAPTCHA verification failed. Please try again.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ============================================================
        // STEP 2: HANDLE SECURE FILE UPLOADS (MULTIPLE FILES, ONE FIELD)
        // ============================================================

        $uploadedDocuments = [];
        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                if ($file->isValid()) {
                    $storedPath = $file->store(
                        'private_applicants_data',
                        'local'
                    );
                    $uploadedDocuments[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'file_path'     => $storedPath,
                        'mime_type'     => $file->getMimeType(),
                        'file_size'     => $file->getSize(),
                    ];
                }
            }
        }

        // ============================================================
        // STEP 3: SAVE DATA TO THE DATABASE
        // ============================================================

        $applicant = Applicant::create([
            'reference_id'      => 'NM-' . strtoupper(Str::random(8)),
            
            'first_name'       => $request->input('first_name'),
            'last_name'        => $request->input('last_name'),
            'address'          => $request->input('address'),
            'city'             => $request->input('city'),
            'state'            => $request->input('state'),
            'email'            => $request->input('email'),
            'phone_number'     => $request->input('phone_number'),

            'company_name'     => $request->input('company_name'),
            'years_employed'   => $request->input('years_employed'),
            'occupation'       => $request->input('occupation'),
            'title'            => $request->input('title'),
            
            'agent_name'       => $request->input('agent_name'),
            'estate_name'      => $request->input('estate_name'),
            'property_address' => $request->input('property_address'),
            'property_cost'    => $request->input('property_cost'),

            'application_status' => 'Pending Payment',
            'payment_status'    => 'Unpaid',
            // we are not storing file paths in DB yet; they are safely stored on disk
        ]);

        foreach ($uploadedDocuments as $document) {
            ApplicantDocument::create([
                'applicant_id' => $applicant->id,
                'document_type'=> 'supporting_document',
                'original_name'=> $document['original_name'],
                'file_path'    => $document['file_path'],
                'mime_type'    => $document['mime_type'],
                'file_size'    => $document['file_size'],
            ]);
        }

        // ============================================================
        // STEP 4: SEND NOTIFICATION EMAIL TO YOU
        // (keep this simple and aligned with current fields)
        // ============================================================

        $dataForEmail = [
            'first_name'    => $request->input('first_name'),
            'last_name'     => $request->input('last_name'),
            'email'         => $request->input('email'),
            'phone_number'  => $request->input('phone_number'),
            'address'       => $request->input('address'),
            'city'          => $request->input('city'),
            'state'         => $request->input('state'),
            'company_name'  => $request->input('company_name'),
            'years_employed'=> $request->input('years_employed'),
            'occupation'    => $request->input('occupation'),
            'title'         => $request->input('title'),
            'agent_name'    => $request->input('agent_name'),
            'estate_name'   => $request->input('estate_name'),
            'property_address' => $request->input('property_address'),
            'property_cost' => $request->input('property_cost'),
        ];

        //Mail details to me
        Mail::to(env('MORTGAGE_APPLICATION_EMAIL'))
            ->send(new MortgageApplicationReceived(
                $dataForEmail,
                collect($uploadedDocuments)->pluck('file_path')->toArray(),
                'admin'
            ));
        
        // Send a copy/ confirmation to the applicant
        if ($request->filled('email') && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)
                ->send(new MortgageApplicationReceived(
                    $dataForEmail,
                    collect($uploadedDocuments)->pluck('file_path')->toArray(),
                    'applicant'
                ));
        }


        // ============================================================
        // STEP 5: REDIRECT USER TO SUCCESS PAGE
        // ============================================================

        return redirect()
            ->route('application.payment', $applicant->id);
    }

    /**
     * Show the success page after a submission.
     */
    public function showSuccess()
    {
        return view('success');
    }

    public function showPaymentPage($id)
    {
        $applicant = Applicant::findOrFail($id);

        return view('payment', compact('applicant'));
    }

    public function submitPaymentReceipt(Request $request, $id)
    {
        $request->validate([
            'payment_receipt' => 'required|mimes:jpg,jpeg,png,pdf|max:32768',
        ]);

        $applicant = Applicant::findOrFail($id);

        $receiptPath = null;

        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')
                ->store('payment_receipts', 'local');
        }

        $applicant->update([
            'receipt_path' => $receiptPath,
            'payment_status' => 'Pending Verification',
            'application_status' => 'Payment Receipt Submitted',
            'payment_submitted_at' => now(),
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'payment_receipt',
            'message' => 'Payment receipt uploaded.',
            'performed_by' => 'Applicant',
        ]);
        
        $applicant->refresh();

        Mail::to(env('MORTGAGE_APPLICATION_EMAIL'))
            ->send(new PaymentReceiptSubmitted($applicant));

        return redirect()
            ->route('application.success')
            ->with(
                'success_message',
                'Your payment receipt has been acknowledged successfully. We shall contact you via email along with a WhatsApp notification within 24 hours. Please keep your WhatsApp open.'
            );
    }
}
