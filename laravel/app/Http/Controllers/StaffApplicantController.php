<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Cache;

class StaffApplicantController extends Controller
{
    public function show(Applicant $applicant)
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
        
        $applicant->load([
            'documents:id,applicant_id,original_name',
            'notes' => fn ($query) => $query
                ->select('id', 'applicant_id', 'note', 'created_by', 'created_at')
                ->latest(),
            'tasks' => fn ($query) => $query
                ->select('id', 'applicant_id', 'task', 'due_date', 'status', 'created_at')
                ->latest(),
            'timeline' => fn ($query) => $query
                ->select('id', 'applicant_id', 'event_type', 'message', 'performed_by', 'created_at')
                ->latest(),
            'statusHistories' => fn ($query) => $query
                ->select('id', 'applicant_id', 'old_status', 'new_status', 'changed_by', 'created_at')
                ->latest(),
            'payments' => fn ($query) => $query
                ->select('id', 'applicant_id', 'provider', 'reference', 'amount', 'status', 'created_at')
                ->latest(),
            'assignedStaffUser:id,first_name,last_name,email,role',
        ]);

        $assignableStaffUsers = Cache::remember(
            'staff.assignable_users',
            now()->addMinutes(5),
            fn () => StaffUser::where('is_active', true)
                ->where('account_status', 'active')
                ->orderByRaw('LOWER(first_name)')
                ->orderByRaw('LOWER(last_name)')
                ->get()
        );
    
        return view('staff.show-applicant', compact('applicant', 'assignableStaffUsers'));
    }
}
