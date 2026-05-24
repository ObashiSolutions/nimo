<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use App\Models\ApplicantStatusHistory;
use Illuminate\Support\Facades\Auth;

class StaffApplicationController extends Controller
{
    public function updateStatus(Request $request, Applicant $applicant)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if ($currentStaffUser->role === 'support') {
            return back()->withErrors('Support users cannot change application status.');
        }

        if (
            $currentStaffUser->role === 'reviewer'
            && (int) $applicant->assigned_staff_user_id !== (int) $currentStaffUser->id
        ) {
            return back()->withErrors('Reviewers can only update applications assigned to them.');
        }

        $validated = $request->validate([
            'application_status' => 'required|string|in:Pending Payment,Pending Verification,In Review,Approved,Rejected,Closed',
        ]);

        if (
            $currentStaffUser->role === 'reviewer'
            && in_array($validated['application_status'], ['Approved', 'Closed'])
        ) {
            return back()->withErrors('Reviewers cannot approve or close applications.');
        }

        $oldStatus = $applicant->application_status;

        $applicant->update([
            'application_status' => $validated['application_status'],
        ]);

        ApplicantStatusHistory::create([
            'applicant_id' => $applicant->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['application_status'],
            'changed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'status_update',
            'message' => 'Application status updated to: ' . $validated['application_status'],
            'performed_by' => $currentStaffUser?->first_name,
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Status Change',
            'description' =>
                'Application status changed from "' .
                $oldStatus .
                '" to "' .
                $validated['application_status'] .
                '".',
            'performed_by' => $currentStaffUser?->first_name ?? 'Staff',
        ]);

        return back()->with('success_message', 'Application status updated.');
    }
}