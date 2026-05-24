<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantNote;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use Illuminate\Support\Facades\Auth;

class ApplicantNoteController extends Controller
{
    public function store(Request $request, Applicant $applicant)
    {
        
        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
            &&
            (int) $applicant->assigned_staff_user_id !== (int) $currentStaffUser->id
        ) {
            abort(403);
        }
    
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

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'note',
            'message' => 'Internal note added.',
            'performed_by' => Auth::guard('staff')->user()?->first_name,
        ]);
        
        return back()->with('success_message', 'Note added successfully.');
    }
}