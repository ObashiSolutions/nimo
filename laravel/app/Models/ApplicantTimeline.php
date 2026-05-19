<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApplicantTimeline;

class ApplicantTimeline extends Model
{
    protected $fillable = [
        'applicant_id',
        'event_type',
        'message',
        'performed_by',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}