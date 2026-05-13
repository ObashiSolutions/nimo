<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
// NOTE: The next two lines require you to install the Laravel Excel package first.
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ApplicantsExport; 

class ExportController extends Controller
{
    /**
     * Show the secure admin dashboard view (a basic table of applicants).
     * NOTE: This currently has no password protection. We will add that later.
     */
    public function showDashboard()
    {
        // Retrieve all applicants from the database
        $applicants = Applicant::all();

        // This function will load a simple table view (which we will create next)
        return view('admin.dashboard', compact('applicants'));
    }

    /**
     * Handle the secure data download (Export to Excel/CSV).
     */
    public function exportData()
    {
        return Excel::download(new ApplicantsExport, 'nigeria_mortgage_applicants_' . now()->format('Ymd_His') . '.xlsx');
    }
}