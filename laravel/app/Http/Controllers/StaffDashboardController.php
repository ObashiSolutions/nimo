<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 200);
        $search = request('search');
        $status = request('status');
        $assignedTo = request('assigned_to');
        $sort = request('sort', 'created_at');
        $direction = request('direction', 'desc');

        $allowedSorts = [
            'reference_id',
            'first_name',
            'last_name',
            'email',
            'property_cost',
            'application_status',
            'payment_status',
            'created_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
        

        // Start building the query with eager loading of documents
        $query = Applicant::with('documents', 'assignedStaffUser',);

        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
        ) {
            $query->where('assigned_staff_user_id', $currentStaffUser->id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('estate_name', 'like', "%{$search}%");
            });
        }
        if ($status) {
            $query->where('application_status', $status);
        }
        
        if (
            $assignedTo
            &&
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['admin', 'manager'])
        ) {
            $query->where('assigned_staff_user_id', $assignedTo);
        }

        $query->orderBy($sort, $direction);

        $applicants = $query
            ->paginate($perPage)
            ->withQueryString();
        
        $assignableStaffUsers = \App\Models\StaffUser::where('is_active', true)
            ->orderBy('first_name')
            ->get();
        
        return view(
            'staff.dashboard',
            compact('applicants', 'assignableStaffUsers')
        );
    }
}