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
            // Only deduct if status is approved and not already deducted
            if ($leave->status === 'approved') {
                $year = date('Y', strtotime($leave->start_date));
                $days = (new \Carbon\Carbon($leave->end_date))->diffInDays(new \Carbon\Carbon($leave->start_date)) + 1;
                $balance = LeaveBalance::where('user_id', $leave->user_id)
                    ->where('leave_type_id', $leave->leave_type_id)
                    ->where('year', $year)
                    ->first();
                if ($balance && $balance->balance >= $days) {
                    $balance->balance -= $days;
                    $balance->save();
                }
            }
        });
    }
}
