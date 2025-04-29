<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('role_permissions.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create($request->only('name'));
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role_permissions.index')->with('success', 'Role and permissions created successfully');
    }

    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions);

        return redirect()->route('role_permissions.index')->with('success', 'Permissions updated successfully');
    }
}

