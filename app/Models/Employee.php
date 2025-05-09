<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeEmploymentHistory; // Ensure this class exists in the specified namespace

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'alias',
        'hiring_date',
        'birthday',
        'gender',
        'last_day',
        'email',
        'sss_number',
        'pag_ibig_number',
        'philhealth_number',
        'tin',
        'atm_account_number',
        'bank',
        'phone_number',
        'agency_id',
        'department_id',
        'cdm_level_id',
        'position_id',
        'employment_status_id',
        'basic_pay',
        'rata',
        'comm_allowance',
        'transpo_allowance',
        'parking_toll_allowance',
        'clothing_allowance',
        'address',
        'civil_status'
    ];

    protected $casts = [
        'birthday' => 'date',
        'hiring_date' => 'date',
        'last_day' => 'date'
    ];

    protected $appends = [
        'exists',
        'full_name',
        'employee_number_formatted',
        'is_new',
        'has_government_ids'
    ];

    // Relationships
    public function personalInfo()
    {
        return $this->hasOne(EmployeePersonalInfo::class);
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function dependents()
    {
        return $this->hasMany(EmployeeDependent::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmployeeEmergencyContact::class);
    }

    public function employmentHistories()
    {
        return $this->hasMany(EmployeeEmploymentHistory::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function cdmLevel()
    {
        return $this->belongsTo(CdmLevel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Attributes
    public static function getFormattedNextNumber()
    {
        $lastEmployee = self::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
        return str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function getExistsAttribute()
    {
        return (bool) $this->id;
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} " . ($this->name_suffix ?? ''));
    }

    public function getEmployeeNumberFormattedAttribute()
    {
        return str_pad($this->employee_number, 6, '0', STR_PAD_LEFT);
    }

    public function getIsNewAttribute()
    {
        return !$this->exists;
    }

    public function getHasGovernmentIdsAttribute()
    {
        return $this->hasGovernmentIds();
    }

    // Business Logic Methods
    public function hasGovernmentIds()
    {
        return !empty($this->sss_number) || 
               !empty($this->tin) || 
               !empty($this->philhealth_number) || 
               !empty($this->pag_ibig_number);
    }

    public function isComplete()
    {
        return $this->personalInfo && 
               $this->hasGovernmentIds() &&
               $this->employmentHistories()->exists() && 
               $this->educations()->exists() && 
               $this->dependents()->exists() && 
               $this->emergencyContacts()->exists();
    }

    // Access compensation fields directly (since they're in the employees table)
    public function getCompensationAttribute()
    {
        return [
            'basic_pay' => $this->basic_pay,
            'rata' => $this->rata,
            'comm_allowance' => $this->comm_allowance,
            'transpo_allowance' => $this->transpo_allowance,
            'parking_toll_allowance' => $this->parking_toll_allowance,
            'clothing_allowance' => $this->clothing_allowance
        ];
    }

    // Access contact info fields directly
    public function getContactInfoAttribute()
    {
        return [
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'bank' => $this->bank,
            'atm_account_number' => $this->atm_account_number
        ];
    }

    public function getEmploymentAttribute()
    {
        return [
            'agency_id' => $this->agency_id,
            'department_id' => $this->department_id,
            'cdm_level_id' => $this->cdm_level_id,
            'position_id' => $this->position_id,
            'employment_status_id' => $this->employment_status_id
        ];
    }

    
}