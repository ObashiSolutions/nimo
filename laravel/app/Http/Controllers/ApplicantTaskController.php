<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTask;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use Illuminate\Support\Facades\Auth;





class ApplicantTaskController extends Controller
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
            'task' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        ApplicantTask::create([
            'applicant_id' => $applicant->id,
            'task' => $request->task,
            'due_date' => $request->due_date,
            'status' => 'Open',
            'created_by' => 'Staff',
        ]);

        ApplicantActivity::create([
            'applicant_id' => $applicant->id,
            'activity_type' => 'Task Added',
            'description' => 'New follow-up task added.',
            'performed_by' => 'Staff',
        ]);
        
        ApplicantTimeline::create([
            'applicant_id' => $applicant->id,
            'event_type' => 'task_created',
            'message' => 'Task created: ' . $request->title,
            'performed_by' => Auth::guard('staff')->user()?->first_name,
        ]);

        return back()->with('success_message', 'Task added successfully.');
    }

    public function complete(ApplicantTask $task)
    {
        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
            &&
            (int) $task->applicant->assigned_staff_user_id !== (int) $currentStaffUser->id
        ) {
            abort(403);
        }

        $task->update([
            'status' => 'Completed',
        ]);

        ApplicantActivity::create([
            'applicant_id' => $task->applicant_id,
            'activity_type' => 'Task Completed',
            'description' => 'Follow-up task marked as completed.',
            'performed_by' => 'Staff',
        ]);

        return back()->with('success_message', 'Task marked completed.');
    }
}