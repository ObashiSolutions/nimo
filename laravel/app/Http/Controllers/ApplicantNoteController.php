<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantNote;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;

class ApplicantNoteController extends Controller
{
    public function store(Request $request, Applicant $applicant)
    {
        $request->validate([
            'note' => 'required|string|max:5000',
        ]);

        ApplicantNote::create([
            'applicant_id' => $applicant->id,
            'note' => $request->note,
            'created_by' => 'Staff',
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Note Added',
            'description' => 'Internal note added.',
            'performed_by' => 'Staff',
        ]);

        return back()->with('success_message', 'Note added successfully.');
    }
}