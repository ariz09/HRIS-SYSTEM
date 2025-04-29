<?php

// app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name']; // Add other fillable fields as needed

    /**
     * Get all employees for the department
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get all positions for the department
     */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}