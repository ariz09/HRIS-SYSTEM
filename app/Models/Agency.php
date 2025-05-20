<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    // Add 'status' to the fillable array, since it's being used in the controller
    protected $fillable = ['name', 'status', 'logo'];


    // Define the relationship with the Employee model
    public function employees()
    {
        return $this->hasMany(EmploymentInfo::class);
    }
}
