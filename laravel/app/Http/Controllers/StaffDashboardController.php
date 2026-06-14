<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $perPage = (int) request('per_page', 50);
        $perPage = in_array($perPage, [20, 50, 100, 200], true) ? $perPage : 50;

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

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'latest';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query = Applicant::with([
            'documents:id,applicant_id,original_name',
            'assignedStaffUser:id,first_name,last_name,email,role',
        ]);

        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
        ) {
            $query->where('assigned_staff_user_id', $currentStaffUser->id);
        }

        if ($search) {
            $searchTerms = collect(preg_split('/\s+/', trim($search)))
                ->filter()
                ->map(fn ($term) => mb_strtolower($term))
                ->values();

            $searchableColumns = [
                'reference_id',
                'first_name',
                'last_name',
                'address',
                'city',
                'state',
                'email',
                'phone_number',
                'application_status',
                'payment_status',
                'company_name',
                'years_employed',
                'occupation',
                'title',
                'agent_name',
                'estate_name',
                'property_address',
                'property_cost',
            ];

            $query->where(function ($q) use ($searchTerms, $searchableColumns) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($termQuery) use ($term, $searchableColumns) {
                        foreach ($searchableColumns as $column) {
                            $wrappedColumn = $termQuery->getQuery()->getGrammar()->wrap($column);

                            $termQuery->orWhereRaw("LOWER(CAST({$wrappedColumn} AS TEXT)) LIKE ?", ["%{$term}%"]);
                        }

                        $termQuery->orWhereHas('assignedStaffUser', function ($staffQuery) use ($term) {
                            $staffQuery->whereRaw('LOWER(first_name) LIKE ?', ["%{$term}%"])
                                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$term}%"])
                                ->orWhereRaw('LOWER(email) LIKE ?', ["%{$term}%"])
                                ->orWhereRaw('LOWER(role) LIKE ?', ["%{$term}%"]);
                        });
                    });
                }
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

        $assignableStaffUsers = Cache::remember(
            'staff.assignable_users',
            now()->addMinutes(5),
            fn () => StaffUser::where('is_active', true)
                ->where('account_status', 'active')
                ->orderByRaw('LOWER(first_name)')
                ->orderByRaw('LOWER(last_name)')
                ->get()
        );

        return view(
            'staff.dashboard',
            compact('applicants', 'assignableStaffUsers')
        );
    }
}
