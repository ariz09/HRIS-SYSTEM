@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Employee Page</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user me-1"></i>
                    Welcome to Employee Portal
                </div>
            </div>
        </div>
        <div class="card-body">
            <h4>Welcome, {{ Auth::user()->name }}!</h4>
            <p>This is your employee dashboard. You can access your employee-related features here.</p>

            <div class="mt-4">
                <h5>Your Role:</h5>
                <div class="alert alert-info">
                    @foreach(Auth::user()->roles as $role)
                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
