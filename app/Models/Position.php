<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cdm_level_id', 'status'];

    public function cdmLevel()
    {
        return $this->belongsTo(CDMLevel::class);
    }

    public function employees()
    {
        return $this->hasMany(EmploymentInfo::class);
    }
}

