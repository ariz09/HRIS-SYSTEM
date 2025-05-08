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
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <div><i class="fas fa-user me-1"></i> Employee Details</div>
            <div class="btn-group">
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light btn-sm" onclick="return confirm('Are you sure you want to delete this employee?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo" class="rounded-circle" width="80" height="80">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="mb-0">
                        {{ $employee->last_name }}, {{ $employee->first_name }}
                        {{ $employee->middle_name ? $employee->middle_name . ' ' : '' }}
                        {{ $employee->name_suffix ?? '' }}
                    </h4>
                    <p class="text-muted mb-0">{{ optional($employee->position)->name ?? 'N/A' }}</p>
                    <p class="text-muted mb-0">{{ optional($employee->department)->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">Personal Info</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="government-tab" data-bs-toggle="tab" data-bs-target="#government" type="button">Government IDs</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment" type="button">Employment</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">Contact</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="compensation-tab" data-bs-toggle="tab" data-bs-target="#compensation" type="button">Compensation</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button">Education</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dependents-tab" data-bs-toggle="tab" data-bs-target="#dependents" type="button">Dependents</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button">Emergency</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">History</button>
                </li>
                @if($employee->user)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button">User Account</button>
                </li>
                @endif
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="employeeTabContent">
                <!-- Personal Info Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Employee Number</label>
                                    <p class="form-control-plaintext">{{ $employee->employee_number }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Alias</label>
                                    <p class="form-control-plaintext">{{ $employee->alias ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Birthday</label>
                                    <p class="form-control-plaintext">{{ optional($employee->birthday)->format('F d, Y') ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Gender</label>
                                    <p class="form-control-plaintext">{{ ucfirst($employee->gender) ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Civil Status</label>
                                    <p class="form-control-plaintext">{{ ucfirst($employee->civil_status) ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Name Suffix</label>
                                    <p class="form-control-plaintext">{{ $employee->name_suffix ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Government IDs Tab -->
                <div class="tab-pane fade" id="government" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Government IDs</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">SSS Number</label>
                                    <p class="form-control-plaintext">{{ $employee->sss_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Philhealth</label>
                                    <p class="form-control-plaintext">{{ $employee->philhealth_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Pag-Ibig</label>
                                    <p class="form-control-plaintext">{{ $employee->pag_ibig_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">TIN</label>
                                    <p class="form-control-plaintext">{{ $employee->tin ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">ATM Account</label>
                                    <p class="form-control-plaintext">{{ $employee->atm_account_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Bank</label>
                                    <p class="form-control-plaintext">{{ $employee->bank ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Tab -->
                <div class="tab-pane fade" id="employment" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Employment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Agency</label>
                                            <p class="form-control-plaintext">{{ optional($employee->agency)->name ?? 'N/A' }}</p>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Department</label>
                                            <p class="form-control-plaintext">{{ optional($employee->department)->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">CDM Level</label>
                                            <p class="form-control-plaintext">{{ optional($employee->cdmLevel)->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Position</label>
                                            <p class="form-control-plaintext">{{ optional($employee->position)->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-{{ optional($employee->employmentStatus)->name == 'Active' ? 'success' : 'warning' }}">
                                                    {{ optional($employee->employmentStatus)->name ?? 'N/A' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Hiring Date</label>
                                            <p class="form-control-plaintext">{{ optional($employee->hiring_date)->format('F d, Y') ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Last Day</label>
                                            <p class="form-control-plaintext">{{ optional($employee->last_day)->format('F d, Y') ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-plaintext">{{ $employee->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Contact Number</label>
                                    <p class="form-control-plaintext">{{ $employee->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <p class="form-control-plaintext">{{ $employee->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compensation Tab -->
                <div class="tab-pane fade" id="compensation" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Compensation Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Basic Pay</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->basic_pay, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">RATA</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->rata, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Comm Allowance</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->comm_allowance, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Transpo Allowance</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->transpo_allowance, 2) }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Parking/Toll</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->parking_toll_allowance, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Clothing Allowance</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->clothing_allowance, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Other Allowance</label>
                                    <p class="form-control-plaintext">₱{{ number_format($employee->other_allowance, 2) }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold">Total Package</label>
                                    <p class="form-control-plaintext fw-bold">₱{{ number_format($employee->total_package, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education Tab -->
                <div class="tab-pane fade" id="education" role="tabpanel">
                        <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Education Background</h5>
                        </div>
                        <div class="card-body">
                            @if($employee->educations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
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
                                <div class="alert alert-info mb-0">No education records found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dependents Tab -->
                <div class="tab-pane fade" id="dependents" role="tabpanel">
                        <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Dependents</h5>
                        </div>
                        <div class="card-body">
                            @if($employee->dependents->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
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
                                <div class="alert alert-info mb-0">No dependent records found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Emergency Contacts Tab -->
                <div class="tab-pane fade" id="emergency" role="tabpanel">
                        <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Emergency Contacts</h5>
                        </div>
                        <div class="card-body">
                            @if($employee->emergencyContacts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
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
                                <div class="alert alert-info mb-0">No emergency contact records found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Employment History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                        <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Employment History</h5>
                        </div>
                        <div class="card-body">
                            @if($employee->employmentHistories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-light">
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
                                <div class="alert alert-info mb-0">No employment history records found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Account Tab -->
                @if($employee->user)
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">User Account</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Username</label>
                                    <p class="form-control-plaintext">{{ $employee->user->name }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-plaintext">{{ $employee->user->email }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Status</label>
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
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tab functionality
        const tabs = ['personal', 'government', 'employment', 'contact', 'compensation',
            'education', 'dependents', 'emergency', 'history', 'account'
        ];

        // Tab navigation
        $('#nextTabBtn').on('click', function() {
            const currentTab = $('.nav-link.active').attr('id').replace('-tab', '');
            const currentIndex = tabs.indexOf(currentTab);
            if (currentIndex < tabs.length - 1) {
                $(`#${tabs[currentIndex + 1]}-tab`).tab('show');
            }
        });

        $('#prevTabBtn').on('click', function() {
            const currentTab = $('.nav-link.active').attr('id').replace('-tab', '');
            const currentIndex = tabs.indexOf(currentTab);
            if (currentIndex > 0) {
                $(`#${tabs[currentIndex - 1]}-tab`).tab('show');
            }
        });
    });
</script>
@endpush