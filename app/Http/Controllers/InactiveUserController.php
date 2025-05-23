<?php

// app/Http/Controllers/InactiveUserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InactiveUserNotification;
use Illuminate\Http\Request;

class InactiveUserController extends Controller
{
    public function index()
    {
        $inactiveUsers = User::with(['personalInfo', 'employmentInfo'])
            ->where('is_active', false)
            ->get();
            
        return view('admin.inactive-users', compact('inactiveUsers'));
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        
        // Mark notifications for this user as read
        InactiveUserNotification::where('user_id', $user->id)->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'User activated successfully');
    }

    public function getInactiveCount()
    {
        $count = User::where('is_active', false)->count();
        return response()->json(['count' => $count]);
    }
}