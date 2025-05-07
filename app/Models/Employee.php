<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'alias',
        'department_id',
        'position_id',
        'agency_id',
        'cdm_level_id',
        'hiring_date',
        'last_day',
        'employment_status_id',
        'gender_id',
        'user_id',
        'basic_pay',
        'rata',
        'comm_allowance',
        'transpo_allowance',
        'parking_toll_allowance',
        'clothing_allowance',
        'total_package',
        'sss_number',
        'philhealth_number',
        'pag_ibig_number',
        'tin',
        'email',
        'phone_number',
        'address',
        'birthday',
        'civil_status',
        'atm_account_number',
        'bank'
    ];

    protected $casts = [
        'hiring_date' => 'date',
        'last_day' => 'date',
        'birthday' => 'date',
        'basic_pay' => 'decimal:2',
        'rata' => 'decimal:2',
        'comm_allowance' => 'decimal:2',
        'transpo_allowance' => 'decimal:2',
        'parking_toll_allowance' => 'decimal:2',
        'clothing_allowance' => 'decimal:2',
        'total_package' => 'decimal:2'
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

    public function leaveApplications(): HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->name_suffix}");
    }

    public function getFormalNameAttribute(): string
    {
        return trim("{$this->last_name}, {$this->first_name}" . ($this->middle_name ? " {$this->middle_name}" : ''));
    }
}