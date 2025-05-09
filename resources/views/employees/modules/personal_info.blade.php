@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item">
            @if($employee->exists)
                <a href="{{ route('employees.edit', $employee) }}">Employee Modules</a>
            @else
                <a href="{{ route('employees.index') }}">Employee Modules</a>
            @endif
        </li>
        <li class="breadcrumb-item active">Personal Information</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-user me-1"></i> Personal Information
        </div>
        <div class="card-body">
            <form action="{{ route('employees.personal.update', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Employee Number</label>
                        <input type="text" class="form-control" value="{{ $formattedEmployeeNumber }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $employee->middle_name) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Suffix</label>
                        <input type="text" name="suffix" class="form-control" value="{{ old('suffix', $employee->suffix) }}">
                    </div>
                </div>

                <!-- Add other personal info fields here -->

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Personal Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection