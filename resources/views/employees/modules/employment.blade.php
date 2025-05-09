@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Employment Information</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-briefcase me-1"></i> Employment Information
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.employment', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Agency</label>
                        <select name="agency_id" class="form-select" required>
                            <option value="">Select Agency</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id', $employee->agency_id) == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Department</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Position</label>
                        <select name="position_id" class="form-select" required>
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Employment Status</label>
                        <select name="employment_status_id" class="form-select" required>
                            <option value="">Select Status</option>
                            @foreach($employmentStatuses as $status)
                                <option value="{{ $status->id }}" {{ old('employment_status_id', $employee->employment_status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">CDM Level</label>
                        <select name="cdm_level_id" class="form-select" required>
                            <option value="">Select Level</option>
                            @foreach($cdmLevels as $level)
                                <option value="{{ $level->id }}" {{ old('cdm_level_id', $employee->cdm_level_id) == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Hire Date</label>
                        <input type="date" name="hire_date" class="form-control" 
                               value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Bank</label>
                        <input type="text" name="bank" class="form-control" 
                               value="{{ old('bank', $employee->bank) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">ATM Account Number</label>
                        <input type="text" name="atm_account_number" class="form-control" 
                               value="{{ old('atm_account_number', $employee->atm_account_number) }}">
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Employment Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection