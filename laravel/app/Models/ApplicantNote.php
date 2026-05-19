<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantNote extends Model
{
    protected $fillable = [
        'applicant_id',
        'note',
        'created_by',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}