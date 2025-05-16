<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'dependent_type',
        'full_name',
        'birthdate',
        'contact_number'
    ];
    
    protected $dates = ['birthdate'];

    public function employee()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }
    
    public function employmentInfo()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }
}