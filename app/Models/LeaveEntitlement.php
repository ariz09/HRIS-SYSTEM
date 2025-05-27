<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_type_id',
        'employee_level',
        'days_allowed',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
