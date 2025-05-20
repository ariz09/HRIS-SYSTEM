<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File201 extends Model
{
    use HasFactory;

    protected $table = 'file201s';

    protected $fillable = [
        'employee_number',
        'file_type',
        'attachment',
        'user_id',
    ];

    // Relationship to User (uploader)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static list of file types
    public static function fileTypes()
    {
        return [
            'signed contract docs',
            'proof of identification numbers',
            'medical results',
            'valid ids',
            'certificates',
            'house sketch',
            'e-sign',
            'other pre-employment documents'
        ];
    }
}
