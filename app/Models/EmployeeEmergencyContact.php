<?php

namespace App\Models;

use Database\Factories\EmployeeInfoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'fullname',
        'relationship',
        'contact_number',
        'address'
    ];

    

    public function employee()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }
    
    public function employmentInfo()
{
    return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
}
}