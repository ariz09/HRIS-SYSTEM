<?php

namespace App\Models;
use App\Models\Dependent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $fillable = [
        'employee_number', 'first_name', 'middle_name', 'last_name', 'name_suffix', 'alias',
        'department_id', 'position_id', 'agency_id', 'cdm_level_id', 'hiring_date', 'last_day',
        'employment_status_id', 'gender_id', 'user_id', 'basic_pay', 'transpo_allowance',
        'sss_number', 'philhealth_number', 'email', 'phone_number', 'address'
    ];

    public static function getFormattedNextNumber()
    {
        $latest = self::orderBy('employee_number', 'desc')->first();
        $nextNumber = $latest ? ((int)$latest->employee_number + 1) : 1;
        return str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function cdmLevel(): BelongsTo
    {
        return $this->belongsTo(CdmLevel::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(Dependent::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function employmentHistories(): HasMany
    {
        return $this->hasMany(EmploymentHistory::class);
    }
}