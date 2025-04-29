@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{ isset($employee) ? 'Edit' : 'Create' }} Employee</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user me-1"></i> Employee Information
        </div>
        <div class="card-body">
            <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($employee))
                    @method('PUT')
                @endif

                <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button">Personal Info</button>
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
                </ul>

                <div class="tab-content" id="employeeTabContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_number" class="form-label">Employee Number *</label>
                                <input type="text" class="form-control @error('employee_number') is-invalid @enderror" 
                                    id="employee_number" name="employee_number" 
                                    value="{{ old('employee_number', $employee->employee_number ?? $formattedEmployeeNumber ?? '') }}" 
                                    required readonly>
                                @error('employee_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" 
                                       value="{{ old('first_name', $employee->first_name ?? '') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                       value="{{ old('middle_name', $employee->middle_name ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" 
                                       value="{{ old('last_name', $employee->last_name ?? '') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $employee->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $employee->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $employee->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" 
                                       value="{{ old('birthday', $employee->birthday ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="civil_status" class="form-label">Civil Status</label>
                                <select class="form-select" id="civil_status" name="civil_status">
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('civil_status', $employee->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('civil_status', $employee->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ old('civil_status', $employee->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="divorced" {{ old('civil_status', $employee->civil_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Info Tab -->
                        <div class="tab-pane fade" id="employment" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="department_id" class="form-label">Department *</label>
                                    <select class="form-select @error('department_id') is-invalid @enderror" 
                                            id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                {{ old('department_id', $employee->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                  <!-- CDM Level Field -->
                                  <div class="col-md-6 mb-3">
                                    <label for="cdm_level_id" class="form-label">CDM Level *</label>
                                    <select class="form-select @error('cdm_level_id') is-invalid @enderror" 
                                            id="cdm_level_id" name="cdm_level_id" required>
                                        <option value="">Select CDM Level</option>
                                        @foreach($cdmLevels as $cdmLevel)
                                            <option value="{{ $cdmLevel->id }}" 
                                                {{ old('cdm_level_id', $employee->cdm_level_id ?? '') == $cdmLevel->id ? 'selected' : '' }}>
                                                {{ $cdmLevel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cdm_level_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="position_id" class="form-label">Position *</label>
                                    <select class="form-select @error('position_id') is-invalid @enderror" 
                                            id="position_id" name="position_id" required>
                                        <option value="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" 
                                                {{ old('position_id', $employee->position_id ?? '') == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="hiring_date" class="form-label">Hiring Date *</label>
                                    <input type="date" class="form-control @error('hiring_date') is-invalid @enderror" 
                                        id="hiring_date" name="hiring_date" 
                                        value="{{ old('hiring_date', $employee->hiring_date ?? '') }}" required>
                                    @error('hiring_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="employment_status" class="form-label">Employment Status *</label>
                                    <select class="form-select @error('employment_status') is-invalid @enderror" 
                                            id="employment_status" name="employment_status" required>
                                        <option value="">Select Status</option>
                                        @foreach($employmentStatuses as $status)
                                            <option value="{{ $status->name }}" 
                                                {{ old('employment_status', $employee->employment_status ?? '') == $status->name ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employment_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                              
                            </div>
                        </div>

                    <!-- Contact Info Tab -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email', $employee->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" 
                                       value="{{ old('phone_number', $employee->phone_number ?? '') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $employee->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Compensation Tab -->
                    <div class="tab-pane fade" id="compensation" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="basic_pay" class="form-label">Basic Pay *</label>
                                <input type="number" step="0.01" class="form-control @error('basic_pay') is-invalid @enderror" 
                                       id="basic_pay" name="basic_pay" 
                                       value="{{ old('basic_pay', $employee->basic_pay ?? '') }}" required>
                                @error('basic_pay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rata" class="form-label">RATA</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="rata" name="rata" 
                                       value="{{ old('rata', $employee->rata ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="comm_allowance" class="form-label">Comm Allowance</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="comm_allowance" name="comm_allowance" 
                                       value="{{ old('comm_allowance', $employee->comm_allowance ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="transpo_allowance" name="transpo_allowance" 
                                       value="{{ old('transpo_allowance', $employee->transpo_allowance ?? '') }}">
                            </div>
                        
                        <div class="col-md-6 mb-3">
                                <label for="transpo_allowance" class="form-label">Parking/Toll</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="parking_toll_allowance" name="parking_toll_allowance" 
                                       value="{{ old('parking_toll_allowance', $employee->parking_toll_allowance ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Clothing Allowance</label>
                                <p class="form-control-plaintext">₱{{ number_format($employee->clothing_allowance, 2) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Package</label>
                                <p class="form-control-plaintext fw-bold">₱{{ number_format($employee->total_package, 2) }}</p>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="sss_number" class="form-label">SSS Number</label>
                                <input type="text" class="form-control" id="sss_number" name="sss_number" 
                                       value="{{ old('sss_number', $employee->sss_number ?? '') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="philhealth_number" class="form-label">PhilHealth Number</label>
                                <input type="text" class="form-control" id="philhealth_number" name="philhealth_number" 
                                       value="{{ old('philhealth_number', $employee->philhealth_number ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Education Tab -->
                    <div class="tab-pane fade" id="education" role="tabpanel">
                        <div id="education-container">
                            @if(isset($employee) && $employee->educations->count() > 0)
                                @foreach($employee->educations as $index => $education)
                                    <div class="education-item card mb-3">
                                        <div class="card-body">
                                            <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Degree</label>
                                                    <input type="text" name="educations[{{ $index }}][degree]" 
                                                           class="form-control" value="{{ $education->degree }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">School</label>
                                                    <input type="text" name="educations[{{ $index }}][school]" 
                                                           class="form-control" value="{{ $education->school }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Year Completed</label>
                                                    <input type="text" name="educations[{{ $index }}][year_completed]" 
                                                           class="form-control" value="{{ $education->year_completed }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="education-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Degree</label>
                                                <input type="text" name="educations[0][degree]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">School</label>
                                                <input type="text" name="educations[0][school]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Year Completed</label>
                                                <input type="text" name="educations[0][year_completed]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-info mb-3" id="add-education">
                            <i class="fas fa-plus"></i> Add Education
                        </button>
                    </div>

                    <!-- Dependents Tab -->
                    <div class="tab-pane fade" id="dependents" role="tabpanel">
                        <div id="dependents-container">
                            @if(isset($employee) && $employee->dependents->count() > 0)
                                @foreach($employee->dependents as $index => $dependent)
                                    <div class="dependent-item card mb-3">
                                        <div class="card-body">
                                            <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="dependents[{{ $index }}][name]" 
                                                           class="form-control" value="{{ $dependent->name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Relationship</label>
                                                    <input type="text" name="dependents[{{ $index }}][relationship]" 
                                                           class="form-control" value="{{ $dependent->relationship }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Birthdate</label>
                                                    <input type="date" name="dependents[{{ $index }}][birthdate]" 
                                                           class="form-control" value="{{ $dependent->birthdate }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="dependent-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="dependents[0][name]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Relationship</label>
                                                <input type="text" name="dependents[0][relationship]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Birthdate</label>
                                                <input type="date" name="dependents[0][birthdate]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-info mb-3" id="add-dependent">
                            <i class="fas fa-plus"></i> Add Dependent
                        </button>
                    </div>

                    <!-- Emergency Contacts Tab -->
                    <div class="tab-pane fade" id="emergency" role="tabpanel">
                        <div id="emergency-container">
                            @if(isset($employee) && $employee->emergencyContacts->count() > 0)
                                @foreach($employee->emergencyContacts as $index => $contact)
                                    <div class="emergency-item card mb-3">
                                        <div class="card-body">
                                            <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][name]" 
                                                           class="form-control" value="{{ $contact->name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Relationship</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][relationship]" 
                                                           class="form-control" value="{{ $contact->relationship }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="emergency_contacts[{{ $index }}][phone]" 
                                                           class="form-control" value="{{ $contact->phone }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="emergency-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="emergency_contacts[0][name]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Relationship</label>
                                                <input type="text" name="emergency_contacts[0][relationship]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Phone</label>
                                                <input type="text" name="emergency_contacts[0][phone]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-info mb-3" id="add-emergency">
                            <i class="fas fa-plus"></i> Add Contact
                        </button>
                    </div>

                    <!-- Employment History Tab -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        <div id="history-container">
                            @if(isset($employee) && $employee->employmentHistories->count() > 0)
                                @foreach($employee->employmentHistories as $index => $history)
                                    <div class="history-item card mb-3">
                                        <div class="card-body">
                                            <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Company Name</label>
                                                    <input type="text" name="employment_histories[{{ $index }}][company_name]" 
                                                           class="form-control" value="{{ $history->company_name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Position</label>
                                                    <input type="text" name="employment_histories[{{ $index }}][position]" 
                                                           class="form-control" value="{{ $history->position }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" name="employment_histories[{{ $index }}][start_date]" 
                                                           class="form-control" value="{{ $history->start_date }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" name="employment_histories[{{ $index }}][end_date]" 
                                                           class="form-control" value="{{ $history->end_date }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="history-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" name="employment_histories[0][company_name]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Position</label>
                                                <input type="text" name="employment_histories[0][position]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" name="employment_histories[0][start_date]" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" name="employment_histories[0][end_date]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-info mb-3" id="add-history">
                            <i class="fas fa-plus"></i> Add History
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="prevTabBtn">
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="nextTabBtn">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        <i class="fas fa-save"></i> Save Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('cdm_level_id').addEventListener('change', function () {
        let cdmLevelId = this.value;
        let positionSelect = document.getElementById('position_id');
        positionSelect.innerHTML = '<option value="">Loading...</option>';
        
        fetch(`/positions/by-cdm-level/${cdmLevelId}`)
            .then(response => response.json())
            .then(data => {
                positionSelect.innerHTML = '<option value="">Select Position</option>';
                data.forEach(position => {
                    positionSelect.innerHTML += `<option value="${position.id}">${position.name}</option>`;
                });
            });
    });
</script>

<script>
$(document).ready(function() {
    // Tab navigation
    const tabs = ['personal', 'employment', 'contact', 'compensation', 'education', 'dependents', 'emergency', 'history'];
    let currentTab = 0;
    
    function showTab(index) {
        // Hide all tabs
        tabs.forEach((tab, i) => {
            $(`#${tab}-tab`).removeClass('active');
            $(`#${tab}`).removeClass('show active');
        });
        
        // Show current tab
        $(`#${tabs[index]}-tab`).addClass('active');
        $(`#${tabs[index]}`).addClass('show active');
        
        // Update button visibility
        $('#prevTabBtn').toggle(index > 0);
        $('#nextTabBtn').toggle(index < tabs.length - 1);
        $('#submitBtn').toggle(index === tabs.length - 1);
    }
    
    $('#nextTabBtn').click(function() {
        if (currentTab < tabs.length - 1) {
            currentTab++;
            showTab(currentTab);
        }
    });
    
    $('#prevTabBtn').click(function() {
        if (currentTab > 0) {
            currentTab--;
            showTab(currentTab);
        }
    });
    
    // Initialize first tab
    showTab(0);
    
    // Dynamic position loading
    $('#department_id').change(function() {
        const departmentId = $(this).val();
        if (departmentId) {
            $.get(`/positions?department_id=${departmentId}`, function(data) {
                $('#position_id').empty().append('<option value="">Select Position</option>');
                $.each(data, function(key, value) {
                    $('#position_id').append(`<option value="${key}">${value}</option>`);
                });
            });
        } else {
            $('#position_id').empty().append('<option value="">Select Position</option>');
        }
    });
    
    // Initialize department/position if editing
    @if(isset($employee) && $employee->department_id)
        $('#department_id').trigger('change').val('{{ $employee->department_id }}');
        setTimeout(() => {
            $('#position_id').val('{{ $employee->position_id }}');
        }, 500);
    @endif

    // Add education
    let educationIndex = {{ isset($employee) ? $employee->educations->count() : 1 }};
    $('#add-education').click(function() {
        let html = `
        <div class="education-item card mb-3">
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Degree</label>
                        <input type="text" name="educations[${educationIndex}][degree]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">School</label>
                        <input type="text" name="educations[${educationIndex}][school]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year Completed</label>
                        <input type="text" name="educations[${educationIndex}][year_completed]" class="form-control">
                    </div>
                </div>
            </div>
        </div>`;
        $('#education-container').append(html);
        educationIndex++;
    });

    // Add dependent
    let dependentIndex = {{ isset($employee) ? $employee->dependents->count() : 1 }};
    $('#add-dependent').click(function() {
        let html = `
        <div class="dependent-item card mb-3">
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="dependents[${dependentIndex}][name]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Relationship</label>
                        <input type="text" name="dependents[${dependentIndex}][relationship]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="dependents[${dependentIndex}][birthdate]" class="form-control">
                    </div>
                </div>
            </div>
        </div>`;
        $('#dependents-container').append(html);
        dependentIndex++;
    });

    // Add emergency contact
    let emergencyIndex = {{ isset($employee) ? $employee->emergencyContacts->count() : 1 }};
    $('#add-emergency').click(function() {
        let html = `
        <div class="emergency-item card mb-3">
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="emergency_contacts[${emergencyIndex}][name]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Relationship</label>
                        <input type="text" name="emergency_contacts[${emergencyIndex}][relationship]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="emergency_contacts[${emergencyIndex}][phone]" class="form-control">
                    </div>
                </div>
            </div>
        </div>`;
        $('#emergency-container').append(html);
        emergencyIndex++;
    });

    // Add employment history
    let historyIndex = {{ isset($employee) ? $employee->employmentHistories->count() : 1 }};
    $('#add-history').click(function() {
        let html = `
        <div class="history-item card mb-3">
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="employment_histories[${historyIndex}][company_name]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Position</label>
                        <input type="text" name="employment_histories[${historyIndex}][position]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="employment_histories[${historyIndex}][start_date]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="employment_histories[${historyIndex}][end_date]" class="form-control">
                    </div>
                </div>
            </div>
        </div>`;
        $('#history-container').append(html);
        historyIndex++;
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.card').remove();
    });
});
</script>
@endpush