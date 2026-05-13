<?php

namespace App\Exports;

use App\Models\Applicant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // This fetches all applicants from the database.
        return Applicant::all();
    }

    /**
     * @var Applicant $applicant
     */
    public function map($applicant): array
    {
        // This function maps the data to the correct columns.
        return [
            $applicant->created_at->format('Y-m-d H:i:s'),
            $applicant->first_name,
            $applicant->last_name,
            $applicant->email,
            $applicant->phone_number,
            $applicant->address,
            $applicant->currency_code,
            $applicant->income,
            $applicant->property_cost,
            $applicant->pdf_path ? 'Yes' : 'No', // Show 'Yes' or 'No' for file uploads
            $applicant->image_path ? 'Yes' : 'No',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // These are the column titles for the exported Excel file.
        return [
            'Submission Date',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Address',
            'Currency',
            'Income',
            'Property Cost',
            'PDF Uploaded?',
            'Image Uploaded?',
        ];
    }
}