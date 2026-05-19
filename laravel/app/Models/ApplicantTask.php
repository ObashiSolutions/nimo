<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTask extends Model
{
    protected $fillable = [
        'applicant_id',
        'task',
        'due_date',
        'status',
        'created_by',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}