<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use App\Models\ApplicantStatusHistory;





class StaffApplicationController extends Controller
{
    public function updateStatus(Request $request, Applicant $applicant)
    {
        $request->validate([
            'application_status' => 'required|string|in:Pending Payment,Pending Verification,In Review,Approved,Rejected,Closed',
        ]);

        $oldStatus = $applicant->application_status;

        $applicant->update([
            'application_status' => $request->application_status,
        ]);

        ApplicantStatusHistory::create([
            'applicant_id' => $applicant->id,
            'old_status' => $oldStatus,
            'new_status' => $request->application_status,
            'changed_by' => Auth::guard('staff')->user()?->first_name,
        ]);

        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'status_update',
            'message' => 'Application status updated to: ' . $request->application_status,
            'performed_by' => Auth::guard('staff')->user()?->first_name,
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Status Change',
            'description' =>
                'Application status changed from "' .
                $oldStatus .
                '" to "' .
                $request->application_status .
                '".',
            'performed_by' => 'Staff',
        ]);

        return back()->with('success_message', 'Application status updated.');
    }
}