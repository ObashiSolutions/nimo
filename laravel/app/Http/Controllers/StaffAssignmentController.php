<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAssignmentController extends Controller
{
    public function assign(Request $request, Applicant $applicant)
    {
        $request->validate([
            'assigned_staff_user_id' => 'nullable|exists:staff_users,id',
        ]);

        $staffUser = StaffUser::find($request->assigned_staff_user_id);

        $applicant->update([
            'assigned_staff_user_id' => $staffUser?->id,
            'assigned_at' => $staffUser ? now() : null,
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'assignment',
            'message' => $staffUser
                ? 'Applicant assigned to ' . $staffUser->first_name . ' ' . $staffUser->last_name
                : 'Applicant unassigned',
            'performed_by' => Auth::guard('staff')->user()?->first_name,
        ]);

        return back()->with('success_message', 'Assignment updated successfully.');
    }
}