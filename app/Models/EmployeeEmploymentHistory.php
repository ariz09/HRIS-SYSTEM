<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmploymentHistory extends Model
{
    use HasFactory;

    protected $table = 'employee_employment_histories';

    protected $fillable = [
        'employee_number',
        'job_title',
        'job_description',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }
}