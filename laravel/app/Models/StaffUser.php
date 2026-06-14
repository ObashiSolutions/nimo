<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffUser extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'account_status',
        'managed_by_staff_user_id',
        'is_active',
        'must_change_password',
        'last_login_at',
        'status_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
        'last_login_at' => 'datetime',
        'status_changed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function manager()
    {
        return $this->belongsTo(self::class, 'managed_by_staff_user_id');
    }

    public function managedStaffUsers()
    {
        return $this->hasMany(self::class, 'managed_by_staff_user_id');
    }

    public function isAvailableForLogin(): bool
    {
        return $this->is_active && $this->account_status === 'active' && !$this->trashed();
    }
}
