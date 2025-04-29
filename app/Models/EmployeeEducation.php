<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;
    protected $table = 'employee_educations';


    protected $fillable = [
        'employee_id', 'school_name', 'course_taken', 'inclusive_dates', 'undergraduate_or_graduate'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
