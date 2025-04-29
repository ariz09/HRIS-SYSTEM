<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'recorded_at',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
