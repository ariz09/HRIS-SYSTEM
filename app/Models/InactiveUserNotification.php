<?php

// app/Models/InactiveUserNotification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InactiveUserNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function countUnread()
    {
        return self::where('is_read', false)->count();
    }

    public static function getInactiveUsers()
    {
        return User::where('is_active', false)->get();
    }
}