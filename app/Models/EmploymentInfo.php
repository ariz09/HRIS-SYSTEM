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
    public function getRouteKeyName()
    {
        return 'employee_number';
    }

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

/*     public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'user_id', 'user_id');
    } */

    public function personalInfo()
    {
        return $this->hasOneThrough(
            PersonalInfo::class,
            User::class,
            'id', // Foreign key on users table
            'user_id', // Foreign key on personal_infos table
            'user_id', // Local key on employment_infos table
            'id' // Local key on users table
        );
    }

    public function compensationPackage()
    {
        return $this->hasOne(CompensationPackage::class, 'employee_number', 'employee_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class, 'employee_number', 'employee_number');
    }

    public function dependents()
    {
        return $this->hasMany(EmployeeDependent::class, 'employee_number', 'employee_number');
    }


    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeEmergencyContact::class, 'employee_number', 'employee_number');
    }

    
    public function employmentHistories()
    {
        return $this->hasMany(EmployeeEmploymentHistory::class, 'employee_number', 'employee_number');
    }
}
