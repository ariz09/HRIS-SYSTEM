<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'name', 'relationship', 'mobile_number', 'present_address'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
