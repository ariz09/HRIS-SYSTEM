@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
    <x-success-alert :message="session('success')" />
    <x-error-alert :message="session('error')" />
    
    <div class="container-fluid px-4">
        <h1 class="mt-4">Employee Management</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">Edit Employee</li>
        </ol>

            <div class="card-body">
                <div class="d-flex justify-content-end flex-wrap gap-2 mb-3">
                    <a href="{{ route('employees.emergency-contacts.edit', $employee->employee_number) }}" class="btn btn-outline-primary">
                        <i class="fas fa-address-book me-1"></i> Emergency Contacts
                    </a>
                    <a href="{{ route('employees.dependents.edit', $employee->employee_number) }}" class="btn btn-outline-warning">
                        <i class="fas fa-users me-1"></i> Dependents
                    </a>
                    <a href="{{ route('employees.educations.edit',  $employee->employee_number) }}" class="btn btn-outline-success">
                        <i class="fas fa-graduation-cap me-1"></i> Education
                    </a>   

                    <a href="{{ route('employees.employment-histories.edit', $employee->employee_number) }}" class="btn btn-outline-info">
                    <i class="fas fa-briefcase me-1"></i> Edit Employment History
                </a>
                </div>

            <form novalidate action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Card -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-danger">
                            <h6 class="mb-0 text-white fw-bold">Personal Information</h6>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-3">
                                <label for="employee_number" class="form-label">Employee ID</label>
                                <input type="text" name="employee_number" id="employee_number" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;" 
                                    value="{{ old('employee_number', $employee->employee_number) }}" readonly required>
                            </div>
                            <div class="col-md-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" 
                                    name="first_name" 
                                    id="first_name" 
                                    class="form-control rounded-2 shadow-sm @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $employee->personalInfo->first_name) }}" 
                                    style="text-transform: uppercase;" 
                                    required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;"
                                    value="{{ old('middle_name', $employee->personalInfo->middle_name) }}">
                                <div class="invalid-feedback">
                                    Please provide a middle name.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;"
                                    value="{{ old('last_name', $employee->personalInfo->last_name) }}" required>
                                    <div class="invalid-feedback">
                                    Please provide a last name.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="name_suffix" class="form-label">Name Suffix</label>
                                <input type="text" name="name_suffix" id="name_suffix" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;" 
                                    value="{{ old('name_suffix', $employee->personalInfo->name_suffix) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="preferred_name" class="form-label">Preferred Name</label>
                                <input type="text" name="preferred_name" id="preferred_name" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;"
                                    value="{{ old('preferred_name', $employee->personalInfo->preferred_name) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select rounded-2 shadow-sm" required>
                                    <option value="Male" {{ $employee->personalInfo->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $employee->personalInfo->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ $employee->personalInfo->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please provide a gender.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" name="birthday" id="birthday" class="form-control rounded-2 shadow-sm"
                                    value="{{ old('birthday', $employee->personalInfo->birthday ?? '') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control rounded-2 shadow-sm"
                                    value="{{ old('email', $employee->personalInfo->email) }}" required>
                                <div class="invalid-feedback">
                                    Please provide an email.
                                </div>
                            </div>
                           <div class="col-md-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" 
                                    name="phone_number" 
                                    id="phone_number" 
                                    class="form-control rounded-2 shadow-sm phone-number"
                                    value="{{ old('phone_number', $employee->personalInfo->phone_number) }}" 
                                    required
                                    pattern="^(09|\+639)\d{9}$">
                                <div class="invalid-feedback">
                                    Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)
                                </div>
                            </div>

                            </div>
                            <div class="col-md-3">
                                <label for="civil_status" class="form-label">Civil Status</label>
                                <select name="civil_status" id="civil_status" class="form-select rounded-2 shadow-sm" required>
                                    <option value="Single" {{ ($employee->personalInfo->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ ($employee->personalInfo->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ ($employee->personalInfo->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ ($employee->personalInfo->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ ($employee->personalInfo->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Employment + Compensation in One Row -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-danger">
                                    <h6 class="mb-0 text-white fw-bold">Employment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="agency_id" class="form-label">Agency</label>
                                        <select name="agency_id" id="agency_id" class="form-select rounded-2 shadow-sm" required>
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}" {{ $agency->id == $employee->agency_id ? 'selected' : '' }}>{{ $agency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="department_id" class="form-label">Department</label>
                                        <select name="department_id" id="department_id" class="form-select rounded-2 shadow-sm" required>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $department->id == $employee->department_id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cdm_level_id" class="form-label">CDM Level</label>
                                        <select name="cdm_level_id" id="cdm_level_id" class="form-select rounded-2 shadow-sm" required>
                                            @foreach($cdmLevels as $cdmLevel)
                                                <option value="{{ $cdmLevel->id }}" {{ $cdmLevel->id == $employee->cdm_level_id ? 'selected' : '' }}>{{ $cdmLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="position_id" class="form-label">Position</label>
                                        <select name="position_id" id="position_id" class="form-select rounded-2 shadow-sm" required>
                                            <option value="">Select Position</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ $position->id == $employee->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hiring_date" class="form-label">Hiring Date</label>
                                        <input type="date" name="hiring_date" id="hiring_date" class="form-control rounded-2 shadow-sm" 
                                            value="{{ old('hiring_date', $employee->hiring_date) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="employment_status_id" class="form-label">Employment Status</label>
                                        <select name="employment_status_id" id="employment_status_id" class="form-select rounded-2 shadow-sm">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $status->id == $employee->employment_status_id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="employment_type_id" class="form-label">Employment Type</label>
                                        <select name="employment_type_id" id="employment_type_id" class="form-select rounded-2 shadow-sm" required>
                                            @foreach($employmentTypes as $type)
                                                <option value="{{ $type->id }}" {{ $type->id == $employee->employment_type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compensation Package Card -->
                        <div class="col-md-6">
                            <div class="card mb-4 shadow-sm border-0">
                                <div class="card-header bg-danger">
                                    <h6 class="mb-0 text-white fw-bold">Compensation Package</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="basic_pay" class="form-label">Basic Pay</label>
                                        <input type="number" name="basic_pay" id="basic_pay" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('basic_pay', $employee->compensationPackage->basic_pay) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="rata" class="form-label">RATA</label>
                                        <input type="number" name="rata" id="rata" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('rata', $employee->compensationPackage->rata) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="comm_allowance">Commission Allowance</label>
                                        <input type="number" name="comm_allowance" id="comm_allowance" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('comm_allowance', $employee->compensationPackage->comm_allowance) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
                                        <input type="number" name="transpo_allowance" id="transpo_allowance" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('transpo_allowance', $employee->compensationPackage->transpo_allowance) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="parking_toll_allowance" class="form-label">Parking/Toll Allowance</label>
                                        <input type="number" name="parking_toll_allowance" id="parking_toll_allowance" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('parking_toll_allowance', $employee->compensationPackage->parking_toll_allowance) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="clothing_allowance" class="form-label">Clothing Allowance</label>
                                        <input type="number" name="clothing_allowance" id="clothing_allowance" class="form-control rounded-2 shadow-sm"
                                            value="{{ old('clothing_allowance', $employee->compensationPackage->clothing_allowance) }}">
                                    </div>
                                   <div class="mb-3">
                                        <label for="atm_account_number" class="form-label">ATM Account Number</label>
                                        <input type="text" 
                                            name="atm_account_number" 
                                            id="atm_account_number" 
                                            class="form-control rounded-2 shadow-sm numeric-only"
                                            value="{{ old('atm_account_number', $employee->compensationPackage->atm_account_number) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;"
                                            value="{{ old('bank_name', $employee->compensationPackage->bank_name) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Submit -->
                    <div class="text-end mt-4 mb-4">
                        
                        <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4">
                            <i class="fas fa-save me-1"></i> Update Employee
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-dark me-2 rounded-pill shadow-sm "><i class="fas fa-times"></i> Cancel</a>
                    </div>



                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize form validation
        setupFormValidation('form');
        
        // Uppercase formatting for specific fields
        const uppercaseFields = ['first_name', 'middle_name', 'last_name', 'name_suffix', 
                               'preferred_name', 'bank_name'];
        uppercaseFields.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', () => {
                    input.value = input.value.toUpperCase();
                });
            }
        });

 
        // CDM Level and Position dynamic loading
        const cdmSelect = document.getElementById('cdm_level_id');
        const positionSelect = document.getElementById('position_id');
        const selectedPositionId = "{{ $employee->position_id }}";

        if (cdmSelect) {
            cdmSelect.addEventListener('change', function () {
                const selectedCDMLevelId = this.value;

                positionSelect.innerHTML = '<option value="">Loading positions...</option>';

                fetch('/positions?cdm_level_id=' + selectedCDMLevelId, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        positionSelect.innerHTML = '<option value="">Select Position</option>';

                        data.positions.forEach(function (position) {
                            const option = new Option(position.name, position.id);
                            if (position.id == selectedPositionId) {
                                option.selected = true;
                            }
                            positionSelect.add(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching positions:', error);
                        positionSelect.innerHTML = '<option value="">Error loading positions</option>';
                    });
            });

            if (cdmSelect.value) {
                cdmSelect.dispatchEvent(new Event('change'));
            }
        }

        // Back button validation
        const backButton = document.querySelector('a[href="{{ route('employees.index') }}"]');
        if (backButton) {
            backButton.addEventListener('click', function(e) {
                let isValid = true;
                document.querySelectorAll('[required]').forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showAlert('warning', 'Please fill in all required fields before going back.');
                }
            });
        }
    });
    </script>

