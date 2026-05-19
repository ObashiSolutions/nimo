<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantActivity extends Model
{
    protected $fillable = [
        'applicant_id',
        'activity_type',
        'description',
        'performed_by',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}