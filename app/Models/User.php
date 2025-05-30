<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;
use App\Models\InactiveUserNotification;

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
        return $this->hasOne(EmploymentInfo::class);
    }

    // app/Models/User.php


    // Add these methods to your User class
    public function inactiveNotifications()
    {
        return $this->hasMany(InactiveUserNotification::class);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getFullNameAttribute()
    {
        if ($this->personalInfo && $this->personalInfo->first_name && $this->personalInfo->last_name) {
            return $this->personalInfo->last_name . ', ' . $this->personalInfo->first_name;
        }

        if ($this->personalInfo && $this->personalInfo->preferred_name) {
            return $this->personalInfo->preferred_name;
        }

        return $this->name;
    }

    public function position()
    {
        return $this->hasOneThrough(Position::class, EmploymentInfo::class, 'user_id', 'id', 'id', 'position_id');
    }
}