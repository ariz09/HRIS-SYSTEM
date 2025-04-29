@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active">{{ $employee->first_name }} {{ $employee->last_name }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-user me-1"></i> Employee Details</div>
            <div>
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        
        <!-- Personal Information -->
        <div class="card-body">
            <h5 class="mb-3">{{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->name_suffix }}</h5>
            <p class="text-muted mb-4">{{ optional($employee->position)->name ?? 'N/A' }}</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee Number</label>
                            <p class="form-control-plaintext">{{ $employee->employee_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alias</label>
                            <p class="form-control-plaintext">{{ $employee->alias ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Birthday</label>
                            <p class="form-control-plaintext">{{ optional($employee->birthday)->format('F d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <p class="form-control-plaintext">{{ $employee->gender ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Civil Status</label>
                            <p class="form-control-plaintext">{{ $employee->civil_status ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <p class="form-control-plaintext">{{ $employee->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <p class="form-control-plaintext">{{ optional($employee->department)->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CDM Level</label>
                            <p class="form-control-plaintext">{{ optional($employee->cdmLevel)->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employment Type</label>
                            <p class="form-control-plaintext">{{ optional($employee->employmentType)->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ optional($employee->employmentStatus)->name == 'Active' ? 'success' : 'warning' }}">
                                    {{ optional($employee->employmentStatus)->name ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <p class="form-control-plaintext">{{ $employee->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <p class="form-control-plaintext">{{ $employee->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hiring Date</label>
                    <p class="form-control-plaintext">{{ optional($employee->hiring_date)->format('F d, Y') ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Day</label>
                    <p class="form-control-plaintext">{{ optional($employee->last_day)->format('F d, Y') ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compensation -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-money-bill-wave me-1"></i> Compensation
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Basic Pay</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->basic_pay, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">RATA</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->rata, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Comm Allowance</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->comm_allowance, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Transpo Allowance</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->transpo_allowance, 2) }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Parking/Toll</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->parking_toll_allowance, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Clothing Allowance</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->clothing_allowance, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Other Allowance</label>
                    <p class="form-control-plaintext">₱{{ number_format($employee->other_allowance, 2) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Total Package</label>
                    <p class="form-control-plaintext fw-bold">₱{{ number_format($employee->total_package, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Government IDs -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-id-card me-1"></i> Government IDs
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">SSS Number</label>
                    <p class="form-control-plaintext">{{ $employee->sss_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Philhealth</label>
                    <p class="form-control-plaintext">{{ $employee->philhealth_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Pag-Ibig</label>
                    <p class="form-control-plaintext">{{ $employee->pag_ibig_number ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">TIN</label>
                    <p class="form-control-plaintext">{{ $employee->tin ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ATM Account</label>
                    <p class="form-control-plaintext">{{ $employee->atm_account_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Education Background -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-graduation-cap me-1"></i> Education Background
        </div>
        <div class="card-body">
            @if($employee->educations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Degree</th>
                                <th>School</th>
                                <th>Year Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->educations as $education)
                            <tr>
                                <td>{{ $education->degree ?? 'N/A' }}</td>
                                <td>{{ $education->school ?? 'N/A' }}</td>
                                <td>{{ $education->year_completed ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No education records found.</p>
            @endif
        </div>
    </div>

    <!-- Dependents -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i> Dependents
        </div>
        <div class="card-body">
            @if($employee->dependents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Relationship</th>
                                <th>Birthdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->dependents as $dependent)
                            <tr>
                                <td>{{ $dependent->name ?? 'N/A' }}</td>
                                <td>{{ $dependent->relationship ?? 'N/A' }}</td>
                                <td>{{ optional($dependent->birthdate)->format('F d, Y') ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No dependent records found.</p>
            @endif
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-phone-alt me-1"></i> Emergency Contacts
        </div>
        <div class="card-body">
            @if($employee->emergencyContacts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Relationship</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->emergencyContacts as $contact)
                            <tr>
                                <td>{{ $contact->name ?? 'N/A' }}</td>
                                <td>{{ $contact->relationship ?? 'N/A' }}</td>
                                <td>{{ $contact->phone ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No emergency contact records found.</p>
            @endif
        </div>
    </div>

    <!-- Employment History -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-briefcase me-1"></i> Employment History
        </div>
        <div class="card-body">
            @if($employee->employmentHistories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->employmentHistories as $history)
                            <tr>
                                <td>{{ $history->company_name ?? 'N/A' }}</td>
                                <td>{{ $history->position ?? 'N/A' }}</td>
                                <td>{{ optional($history->start_date)->format('F d, Y') ?? 'N/A' }}</td>
                                <td>{{ optional($history->end_date)->format('F d, Y') ?? 'Present' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No employment history records found.</p>
            @endif
        </div>
    </div>

    <!-- User Account -->
    @if($employee->user)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-circle me-1"></i> User Account
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Username</label>
                    <p class="form-control-plaintext">{{ $employee->user->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <p class="form-control-plaintext">{{ $employee->user->email }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <p class="form-control-plaintext">
                        @if($employee->user->password_changed)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning">Pending Activation</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection