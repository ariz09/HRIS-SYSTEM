<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignLeave extends Model
{
    protected $fillable = ['cdm_level_id', 'leave_count', 'notes']; // Changed 'department_id' to 'cdm_level_id'

    // Define the relationship to CDMLevel instead of Department
    public function cdmLevel() // Changed 'department' to 'cdmLevel'
    {
        return $this->belongsTo(CDMLevel::class); // Use CDMLevel model instead of Department
    }
}
