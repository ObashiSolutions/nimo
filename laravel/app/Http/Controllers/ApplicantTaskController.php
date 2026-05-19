<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTask;
use Illuminate\Http\Request;
use App\Models\ApplicantActivity;

class ApplicantTaskController extends Controller
{
    public function store(Request $request, Applicant $applicant)
    {
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

        return back()->with('success_message', 'Task added successfully.');
    }

    public function complete(ApplicantTask $task)
    {
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