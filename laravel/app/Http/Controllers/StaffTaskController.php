<?php

namespace App\Http\Controllers;

use App\Models\ApplicantTask;

class StaffTaskController extends Controller
{
    public function index()
        {
            $filter = request('filter');

            $query = ApplicantTask::with('applicant');

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

            return view(
                'staff.tasks',
                compact('tasks')
            );
        }
    }