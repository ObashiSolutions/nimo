<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApplicantDocument;
use App\Models\ApplicantNote;
use App\Models\ApplicantTask;
use App\Models\ApplicantActivity;
use App\Models\ApplicantTimeline;
use App\Models\ApplicantStatusHistory;
use App\Models\Payment;




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
        'reference_id',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'email',
        'phone_number',
        'application_status',
        'payment_status',
        'receipt_path',
        'payment_submitted_at',
        'assigned_staff_user_id',
        'assigned_at',

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
        'assigned_at' => 'datetime',
    ];

    public function documents()
    {
        return $this->hasMany(ApplicantDocument::class);
    }

    public function notes()
    {
        return $this->hasMany(ApplicantNote::class);
    }

    public function tasks()
    {
        return $this->hasMany(ApplicantTask::class);
    }

    public function activities()
    {
        return $this->hasMany(ApplicantActivity::class);
    }

    public function timeline()
    {
        return $this->hasMany(ApplicantTimeline::class)
            ->latest();
    }

    public function statusHistories()
    {
        return $this->hasMany(ApplicantStatusHistory::class)
            ->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function assignedStaffUser()
    {
        return $this->belongsTo(\App\Models\StaffUser::class, 'assigned_staff_user_id');
    }
}
