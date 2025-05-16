<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'hiring_date',
        'employment_status_id',
        'agency_id',
        'department_id',
        'cdm_level_id',
        'position_id',
        'employment_type_id'
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function cdmLevel()
    {
        return $this->belongsTo(CdmLevel::class);
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'user_id', 'user_id');
    }

    public function compensationPackage()
    {
        return $this->hasOne(CompensationPackage::class, 'employee_number', 'employee_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/EmploymentInfo.php
public function dependents()
{
    return $this->hasMany(EmployeeDependent::class, 'employee_number', 'employee_number');
}

    public function emergencyContacts()
{
    return $this->hasMany(EmployeeEmergencyContact::class, 'employee_number', 'employee_number');
}

    public function getRouteKeyName()
    {
        return 'employee_number';
    }
}
