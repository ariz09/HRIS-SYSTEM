<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'birthdate', 'birthplace', 'civil_status', 'gender', 'religion'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
