<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'name_suffix',
        'alias',
        'position_id',
        'department_id',
        'agency_id',
        'cdm_level_id',
        'gender_id',
        'employment_type_id',
        'employment_status_id',
        'hiring_date',
        'last_day',
        'tenure',
        'basic_pay',
        'rata',
        'comm_allowance',
        'transpo_allowance',
        'parking_toll_allowance',
        'clothing_allowance',
        'total_package',
        'atm_account_number',
        'birthday',
        'sss_number',
        'philhealth_number',
        'pag_ibig_number',
        'tin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeRecords()
    {
        return $this->hasMany(TimeRecord::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function cdmLevel()
    {
        return $this->belongsTo(CDMLevel::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class);
    }
}
