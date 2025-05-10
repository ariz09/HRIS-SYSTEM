<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendingUserController extends Controller
{
    public function __construct()
    {
        // Remove the middleware call from constructor
    }

    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $pendingUsers = User::where('is_active', false)
            ->with(['roles'])
            ->latest()
            ->get();

        return view('pending-users.index', compact('pendingUsers'));
    }

    public function activate($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->update(['is_active' => true]);
        });

        return redirect()->route('pending-users.index')
            ->with('success', 'User has been activated successfully.');
    }

    public function reject($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            // Remove user roles
            DB::table('role_user')->where('user_id', $user->id)->delete();

            // Delete the user
            $user->delete();
        });

        return redirect()->route('pending-users.index')
            ->with('success', 'User has been rejected and removed from the system.');
    }
}
