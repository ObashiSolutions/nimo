<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\ApplicantTimeline;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffUser;

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
            'documents',
            'notes',
            'tasks',
            'timeline',
            'statusHistories',
            'payments',
            'assignedStaffUser',
        ]);

        $assignableStaffUsers = StaffUser::where('is_active', true)
            ->orderByRaw('LOWER(first_name)')
            ->orderByRaw('LOWER(last_name)')
            ->get();
    
        return view('staff.show-applicant', compact('applicant', 'assignableStaffUsers'));
    }
}
