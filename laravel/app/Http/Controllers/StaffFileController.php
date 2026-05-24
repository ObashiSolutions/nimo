<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDocument;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;




class StaffFileController extends Controller
{
    public function viewDocument(ApplicantDocument $document)
    {

        $currentStaffUser = Auth::guard('staff')->user();

        if (
            $currentStaffUser
            &&
            in_array($currentStaffUser->role, ['reviewer', 'support'])
            &&
            (int) $document->applicant->assigned_staff_user_id !== (int) $currentStaffUser->id
        ) {
            abort(403);
        }
        
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($document->file_path)
        );
    }

    public function viewReceipt(Applicant $applicant)
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

        if (!$applicant->receipt_path || !Storage::disk('local')->exists($applicant->receipt_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('local')->path($applicant->receipt_path)
        );
    }
}