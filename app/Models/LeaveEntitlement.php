<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_type_id',
        'employee_level',
        'days_allowed',
    ];

    /**
     * Get the leave type that owns the entitlement.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Validate that the requested leave days don't exceed the allowed days.
     */
    public function validateLeaveDays(int $requestedDays): bool
    {
        return $requestedDays <= $this->days_allowed;
    }

    /**
     * Get the leave entitlement for a specific employee level and leave type.
     */
    public static function getEntitlement(string $employeeLevel, int $leaveTypeId): ?self
    {
        return self::where('employee_level', $employeeLevel)
            ->where('leave_type_id', $leaveTypeId)
            ->first();
    }
}
