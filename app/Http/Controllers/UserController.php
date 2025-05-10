<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        // Get role names from IDs
        $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();

        // Sync roles using role names
        $user->syncRoles($roleNames);

        return redirect()->route('users.index')
            ->with('success', 'User roles updated successfully');
    }
}
