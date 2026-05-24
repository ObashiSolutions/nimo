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
            $searchTerms = collect(preg_split('/\s+/', trim($search)))
                ->filter()
                ->map(fn ($term) => mb_strtolower($term))
                ->values();

            $ignoreColumns = [
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
            ];

            $searchableColumns = array_diff($applicantColumns, $ignoreColumns);

            $query->where(function ($q) use ($searchTerms, $searchableColumns) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($termQuery) use ($term, $searchableColumns) {
                        foreach ($searchableColumns as $column) {
                            $wrappedColumn = $termQuery->getQuery()->getGrammar()->wrap($column);

                            $termQuery->orWhereRaw("LOWER({$wrappedColumn}) LIKE ?", ["%{$term}%"]);
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

        $assignableStaffUsers = StaffUser::where('is_active', true)
            ->orderByRaw('LOWER(first_name)')
            ->orderByRaw('LOWER(last_name)')
            ->get();

        return view(
            'staff.dashboard',
            compact('applicants', 'assignableStaffUsers')
        );
    }
}
