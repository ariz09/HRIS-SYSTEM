<?php

namespace App\Models;

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
        return $this->belongsTo(EmployeeInfo::class, 'employee_number', 'employee_number');
    }
}