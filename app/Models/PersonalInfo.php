<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'preferred_name',
        'gender',
        'birthday',
        'email',
        'phone_number',
        'civil_status',
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    /**
     * Get the user associated with the personal info.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
