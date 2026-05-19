<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantStatusHistory extends Model
{
    protected $fillable = [
        'applicant_id',
        'old_status',
        'new_status',
        'changed_by',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}