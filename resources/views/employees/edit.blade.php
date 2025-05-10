@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-pencil-alt me-1"></i> Edit Employee</div>
        </div>
        <div class="card-body">
            <!-- Personal Info Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <!-- Personal Info Fields Here (name, gender, phone, etc.) -->
                    <p>First Name: {{ $employee->personalInfo->first_name }}</p>
                    <p>Last Name: {{ $employee->personalInfo->last_name }}</p>
                    <p>Gender: {{ $employee->personalInfo->gender }}</p>
                    <p>Email: {{ $employee->personalInfo->email }}</p>
                    <p>Phone: {{ $employee->personalInfo->phone_number }}</p>
                </div>
            </div>

            <!-- Employment Info Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Employment Information</h5>
                </div>
                <div class="card-body">
                    <!-- Employment Info Fields Here (employee number, hire date, position, etc.) -->
                    <p>Employee Number: {{ $employee->employee_number }}</p>
                    <p>Hiring Date: {{ $employee->hiring_date }}</p>
                    <p>Position: {{ optional($employee->position)->name }}</p>
                    <p>Department: {{ optional($employee->department)->name }}</p>
                    <p>Status: {{ optional($employee->employmentStatus)->name }}</p>
                </div>
            </div>

            <!-- Compensation Package Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Compensation Package</h5>
                </div>
                <div class="card-body">
                    <!-- Compensation Fields (Basic Pay, Allowances, ATM Account) -->
                    <p>Basic Pay: {{ $employee->compensationPackage->basic_pay }}</p>
                    <p>RATA: {{ $employee->compensationPackage->rata }}</p>
                    <p>Comm. Allowance: {{ $employee->compensationPackage->comm_allowance }}</p>
                    <p>Transport Allowance: {{ $employee->compensationPackage->transpo_allowance }}</p>
                </div>
            </div>

            <!-- Government IDs Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Government IDs</h5>
                </div>
                <div class="card-body">
                    <!-- Government IDs (SSS, PhilHealth, Pag-Ibig, etc.) -->
                    <p>SSS Number: {{ $employee->governmentIds->sss_number }}</p>
                    <p>PhilHealth Number: {{ $employee->governmentIds->philhealth_number }}</p>
                    <p>Pag-Ibig Number: {{ $employee->governmentIds->pagibig_number }}</p>
                </div>
            </div>

            <!-- Employee Dependents Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Employee Dependents</h5>
                </div>
                <div class="card-body">
                    <!-- Employee Dependents Info -->
                    <p>Dependents: {{ $employee->dependents->count() }} dependents</p>
                </div>
            </div>

            <!-- Emergency Contact Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Emergency Contact</h5>
                </div>
                <div class="card-body">
                    <!-- Emergency Contact Info -->
                    <p>Name: {{ $employee->emergencyContact->name }}</p>
                    <p>Phone: {{ $employee->emergencyContact->phone_number }}</p>
                </div>
            </div>

            <!-- Educational Background Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Educational Background</h5>
                </div>
                <div class="card-body">
                    <!-- Educational Info -->
                    <p>Highest Degree: {{ $employee->education->highest_degree }}</p>
                    <p>School: {{ $employee->education->school }}</p>
                    <p>Year Graduated: {{ $employee->education->year_graduated }}</p>
                </div>
            </div>

            <!-- Employment History Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Employment History</h5>
                </div>
                <div class="card-body">
                    <!-- Employment History Info -->
                    <p>Previous Employers: {{ $employee->employmentHistory->count() }} previous employers</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
