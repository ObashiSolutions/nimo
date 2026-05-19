<?php

namespace App\Http\Controllers;

use App\Models\Applicant;

class StaffApplicantController extends Controller
{
    public function show(Applicant $applicant)
    {
        $applicant->load(['documents', 'notes', 'tasks', 'activities']);

        return view('staff.show-applicant', compact('applicant'));
    }
}