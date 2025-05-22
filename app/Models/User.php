<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'temp_password',
        'password_changed',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed' => 'boolean',
    ];

    public function personalInfo()
    {
        return $this->hasOne(\App\Models\PersonalInfo::class, 'user_id');
    }


    public function employmentInfo()
    {
        return $this->hasOne(\App\Models\EmploymentInfo::class, 'user_id');
    }


}
