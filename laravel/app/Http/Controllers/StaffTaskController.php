<?php

namespace App\Http\Controllers;

use App\Models\ApplicantTask;
use Illuminate\Support\Facades\Auth;

class StaffTaskController extends Controller
{
    public function index()
    {
        $filter = request('filter');

        $currentStaffUser = Auth::guard('staff')->user();

        $query = ApplicantTask::with([
            'applicant',
            'applicant.assignedStaffUser',
        ]);

        if (
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
        ) {
            $query->whereHas('applicant', function ($q) use ($currentStaffUser) {
                $q->where('assigned_staff_user_id', $currentStaffUser->id);
            });
        }

        switch ($filter) {
            case 'open':
                $query->where('status', 'Open');
                break;

            case 'completed':
                $query->where('status', 'Completed');
                break;

            case 'overdue':
                $query->where('status', '!=', 'Completed')
                    ->whereDate('due_date', '<', now());
                break;

            case 'today':
                $query->whereDate('due_date', now());
                break;
        }

        $tasks = $query
            ->latest()
            ->paginate(200)
            ->withQueryString();

        return view('staff.tasks', compact('tasks'));
    }
}