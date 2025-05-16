<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;
    protected $table = "employee_educations";
    protected $fillable = [
        'school_name',
        'course_taken',
        'year_finished',
        'status',
        'employee_number',
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