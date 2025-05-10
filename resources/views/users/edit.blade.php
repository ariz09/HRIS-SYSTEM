@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit User Roles</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Management</a></li>
        <li class="breadcrumb-item active">Edit User</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Edit User: {{ $user->name }}
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">User Information</label>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Roles</label>
                    <div class="row">
                        @foreach($roles as $role)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->id }}"
                                    id="role{{ $role->id }}"
                                    {{ $user->hasRole($role) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Roles
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
