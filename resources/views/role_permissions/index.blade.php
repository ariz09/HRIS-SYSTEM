@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Role Permissions Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Role Permissions</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Role Permissions
                </div>
                <div>
                    <button type="button" class="btn btn-light text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fas fa-plus"></i> Add Role
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="rolesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach ($role->permissions as $permission)
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('role_permissions.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>
                        <select multiple class="form-select" id="permissions" name="permissions[]">
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Create Role</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
@foreach ($roles as $role)
<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('role_permissions.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role Permissions</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="permissions" class="form-label">Permissions</label>
                        <select multiple class="form-select" id="permissions" name="permissions[]">
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'selected' : '' }}>
                                    {{ $permission->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
