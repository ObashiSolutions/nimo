<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 200);
        $search = request('search');
        $status = request('status');
        $assignedTo = request('assigned_to');
        $sort = request('sort', 'latest');
        $direction = request('direction', 'desc');

        $allowedSorts = [
            'latest',
            'oldest',
            'reference_id',
            'first_name',
            'last_name',
            'email',
            'property_cost',
            'application_status',
            'payment_status',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $query = Applicant::with('documents', 'assignedStaffUser');

        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
        ) {
            $query->where('assigned_staff_user_id', $currentStaffUser->id);
        }

        if ($search) {
            $applicantColumns = Schema::getColumnListing('applicants');

            $ignoreColumns = [
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
            ];

            $searchableColumns = array_diff($applicantColumns, $ignoreColumns);

            $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }

                $q->orWhereHas('assignedStaffUser', function ($staffQuery) use ($search) {
                    $staffQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('application_status', $status);
        }

        if (
            $assignedTo &&
            $currentStaffUser &&
            in_array($currentStaffUser->role, ['admin', 'manager'])
        ) {
            $query->where('assigned_staff_user_id', $assignedTo);
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sort, $direction);
        }

        $applicants = $query
            ->paginate($perPage)
            ->withQueryString();

        $assignableStaffUsers = StaffUser::where('is_active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'staff.dashboard',
            compact('applicants', 'assignableStaffUsers')
        );
    }
}