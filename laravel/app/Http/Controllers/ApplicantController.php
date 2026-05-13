<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MortgageApplicationReceived;

class ApplicantController extends Controller
{
    /**
     * Show the main one-page mortgage pre-approval form.
     */
    public function showForm()
    {
        return view('welcome');
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
            'supporting_documents.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:32768', // up to ~32MB each
        ];

        $messages = [
            'email.unique'  => 'The provided email has already submitted an application. Please contact us via WhatsApp if you need to update your details.',
            'property_cost.min' => 'The property cost must be a realistic value.',
            'property_cost.numeric' => 'The property cost must be a number (no commas or symbols).',
            'supporting_documents.*.mimes' => 'Each supporting document must be a PDF, JPG, or PNG file.',
            'supporting_documents.*.max'   => 'Each supporting document must not be larger than 10MB.',
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

        $uploadedPaths = [];

        if ($request->hasFile('supporting_documents')) {
            foreach ($request->file('supporting_documents') as $file) {
                if ($file->isValid()) {
                    $uploadedPaths[] = $file->store('private_applicants_data', 'local');
                }
            }
        }

        // ============================================================
        // STEP 3: SAVE DATA TO THE DATABASE
        // ============================================================

        Applicant::create([
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
            // we are not storing file paths in DB yet; they are safely stored on disk
        ]);

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
            ->send(new MortgageApplicationReceived($dataForEmail, $uploadedPaths));
        
        // Send a copy/ confirmation to the applicant
        if ($request->filled('email') && filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($request->email)
                ->send(new MortgageApplicationReceived($dataForEmail));
        }


        // ============================================================
        // STEP 5: REDIRECT USER TO SUCCESS PAGE
        // ============================================================

        return redirect()
            ->route('application.success')
            ->with('success_message', 'Your secure pre-approval application has been successfully submitted! We shall contact you via email along with a WhatsApp notification within 24 hours. Please keep your WhatsApp open.');
    }

    /**
     * Show the success page after a submission.
     */
    public function showSuccess()
    {
        return view('success');
    }
}
