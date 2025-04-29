<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDMLevel extends Model
{
    use HasFactory;

    protected $table = 'cdm_levels';
    protected $fillable = ['name', 'code', 'description'];

    public function positions()
    {
        return $this->hasMany(Position::class, 'cdm_level_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'cdm_level_id');
    }

    // Add this active scope method
    public function scopeActive($query)
    {
        return $query->where('status', 1); // Assuming the column for active status is 'status' and 1 means active
    }
}
