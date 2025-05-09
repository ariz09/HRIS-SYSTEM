<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $table = 'employee_emergency_contacts';

    protected $fillable = [
        'employee_id',
        'name',
        'relationship',
        'phone'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
