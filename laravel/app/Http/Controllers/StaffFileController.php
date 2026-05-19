<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDocument;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class StaffFileController extends Controller
{
    public function viewDocument(ApplicantDocument $document)
    {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($document->file_path)
        );
    }

    public function viewReceipt(Applicant $applicant)
    {
        if (!$applicant->receipt_path || !Storage::disk('local')->exists($applicant->receipt_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($applicant->receipt_path)
        );
    }
}