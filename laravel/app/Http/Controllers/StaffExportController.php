<?php

namespace App\Http\Controllers;

use App\Models\Applicant;

class StaffExportController extends Controller
{
    public function exportCsv()
    {
        $fileName = 'nigeria-mortgages-applications.csv';

        $applicants = Applicant::latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $columns = [
            'Reference ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Address',
            'City',
            'State',
            'Company',
            'Occupation',
            'Years Employed',
            'Title',
            'Agent',
            'Estate',
            'Property Address',
            'Property Cost',
            'Application Status',
            'Payment Status',
            'Receipt Path',
            'Submitted At',
        ];

        $callback = function () use ($applicants, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($applicants as $applicant) {
                fputcsv($file, [
                    $applicant->reference_id,
                    $applicant->first_name,
                    $applicant->last_name,
                    $applicant->email,
                    $applicant->phone_number,
                    $applicant->address,
                    $applicant->city,
                    $applicant->state,
                    $applicant->company_name,
                    $applicant->occupation,
                    $applicant->years_employed,
                    $applicant->title,
                    $applicant->agent_name,
                    $applicant->estate_name,
                    $applicant->property_address,
                    $applicant->property_cost,
                    $applicant->application_status,
                    $applicant->payment_status,
                    $applicant->receipt_path,
                    $applicant->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}