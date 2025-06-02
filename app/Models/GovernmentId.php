<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentId extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'sss_number',
        'pag_ibig_number',
        'philhealth_number',
        'tin'
    ];

    public function employmentInfo()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }
}