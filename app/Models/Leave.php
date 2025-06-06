<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeaveBalance;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'duration',
        'start_date',
        'end_date',
        'reason',
        'contact_number',
        'address_during_leave',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    protected static function booted()
    {
        static::saved(function ($leave) {
            // We're handling balance deduction in the controller
            // This event handler is no longer needed
        });
    }
}
