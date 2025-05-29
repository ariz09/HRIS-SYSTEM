<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excesstime extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'start',
        'end',
        'reason',
        'status',
        'remarks',
        'user_id'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(EmploymentInfo::class, 'employee_number', 'employee_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}