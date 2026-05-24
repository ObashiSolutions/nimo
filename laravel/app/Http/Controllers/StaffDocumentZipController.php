<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Auth;



class StaffDocumentZipController extends Controller
{
    public function download(Applicant $applicant)
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
        
        $applicant->load('documents');

        if ($applicant->documents->count() <= 1) {
            return back()->withErrors([
                'zip' => 'ZIP download is only available when there is more than one document.',
            ]);
        }

        $zipFileName = $applicant->reference_id . '-documents.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors([
                'zip' => 'Could not create ZIP file.',
            ]);
        }

        foreach ($applicant->documents as $document) {
            if (Storage::disk('local')->exists($document->file_path)) {
                $zip->addFile(
                    Storage::disk('local')->path($document->file_path),
                    $document->original_name ?? basename($document->file_path)
                );
            }
        }

        $zip->close();

        return response()
            ->download($zipPath, $zipFileName)
            ->deleteFileAfterSend(true);
    }
}