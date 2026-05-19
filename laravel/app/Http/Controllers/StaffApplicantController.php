<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use Illuminate\Support\Facades\Auth;

class StaffApplicantController extends Controller
{
    public function show(Applicant $applicant)
    {
        $applicant->load([
            'documents',
            'notes',
            'tasks',
            'timeline',
        ]);

        return view('staff.show-applicant', compact('applicant'));
    }
}