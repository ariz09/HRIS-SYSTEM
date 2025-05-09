@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Government IDs</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-id-card me-1"></i> Government IDs
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.government', $employee) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">SSS Number</label>
                        <input type="text" name="sss" class="form-control" 
                               value="{{ old('sss', $employee->sss) }}"
                               placeholder="XX-XXXXXXX-X">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">TIN</label>
                        <input type="text" name="tin" class="form-control" 
                               value="{{ old('tin', $employee->tin) }}"
                               placeholder="XXX-XXX-XXX-XXX">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Philhealth</label>
                        <input type="text" name="philhealth" class="form-control" 
                               value="{{ old('philhealth', $employee->philhealth) }}"
                               placeholder="XX-XXXXXXXXX-X">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pag-IBIG</label>
                        <input type="text" name="pagibig" class="form-control" 
                               value="{{ old('pagibig', $employee->pagibig) }}"
                               placeholder="XXXX-XXXX-XXXX">
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Government IDs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection