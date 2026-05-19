<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantDocument extends Model
{
    protected $fillable = [
        'applicant_id',
        'document_type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}