<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'applicant_id',
        'provider',
        'reference',
        'amount',
        'currency',
        'status',
        'authorization_url',
        'provider_response',
    ];

    protected $casts = [
        'provider_response' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}