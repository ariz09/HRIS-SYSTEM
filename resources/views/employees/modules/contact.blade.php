@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Contact Information</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-address-book me-1"></i> Contact Information
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.contact', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Email Address</label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $employee->email) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" 
                               value="{{ old('phone_number', $employee->phone_number) }}" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label required">Address</label>
                        <input type="text" name="address" class="form-control" 
                               value="{{ old('address', $employee->address) }}" required>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Contact Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection