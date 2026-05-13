<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    /**
     * Table name
     */
    protected $table = 'applicants';

    /**
     * Mass-assignable fields matching the final form + controller
     */
    protected $fillable = [
        // 1. PERSONAL DATA
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'email',
        'phone_number',

        // 2. WORK INFORMATION
        'company_name',
        'years_employed',
        'occupation',
        'title',

        // 3. PROPERTY INFORMATION
        'agent_name',
        'estate_name',
        'property_address',
        'property_cost',
    ];

    /**
     * No hidden fields required
     */
    protected $hidden = [];

    /**
     * Cast values
     */
    protected $casts = [
        'property_cost' => 'integer',
        'years_employed' => 'integer',
    ];
}
