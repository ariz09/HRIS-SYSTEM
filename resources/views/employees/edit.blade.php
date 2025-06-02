@extends('layouts.app')

<style>
    /* Profile Picture Styles */
    .profile-picture-container {
        width: 150px;
        height: 150px;
        border-radius: 5px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin: 0 auto;
    }

    .profile-picture {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .profile-picture-container {
            width: 120px;
            height: 120px;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-picture-container {
            width: 150px;
            height: 150px;
        }
    }
</style>

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
                <a href="{{ route('employees.emergency-contacts.edit', $employee->employee_number) }}"
                    class="btn btn-outline-danger">
                    <i class="fas fa-address-book me-1"></i> Emergency Contacts
                </a>
                <a href="{{ route('employees.dependents.edit', $employee->employee_number) }}"
                    class="btn btn-outline-danger">
                    <i class="fas fa-users me-1"></i> Dependents
                </a>
                <a href="{{ route('employees.educations.edit', $employee->employee_number) }}"
                    class="btn btn-outline-danger">
                    <i class="fas fa-graduation-cap me-1"></i> Education
                </a>

                <a href="{{ route('employees.employment-histories.edit', $employee->employee_number) }}"
                    class="btn btn-outline-danger">
                    <i class="fas fa-briefcase me-1"></i> Edit Employment History
                </a>
            </div>

            <form novalidate action="{{ route('employees.update', $employee) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <!-- Profile Picture Upload & Preview Card -->
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-danger text-white fw-bold">
                                <i class="fas fa-camera me-1"></i> Profile Picture
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Current Profile Picture Column -->
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="text-center">
                                            <div class="profile-picture-container mx-auto mb-2">
                                                @if($employee->personalInfo && $employee->personalInfo->profile_picture)
                                                    <img id="currentProfilePic"
                                                        src="{{ asset('storage/' . $employee->personalInfo->profile_picture) }}"
                                                        alt="Current Profile Picture" class="img-thumbnail profile-picture">
                                                @else
                                                    <img id="currentProfilePic" src="{{ asset('images/default-user.png') }}"
                                                        alt="Default Profile Picture" class="img-thumbnail profile-picture">
                                                @endif
                                            </div>
                                            <div class="text-muted small mb-3">Current Profile</div>
                                        </div>
                                    </div>

                                    <!-- Preview Column -->
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <div class="profile-picture-container mx-auto mb-2">
                                                <img id="imagePreview" src="{{ asset('images/default-preview.png') }}"
                                                    alt="Image Preview" class="img-thumbnail profile-picture">
                                            </div>
                                            <div class="text-muted small mb-3">New Preview</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Controls (Full Width) -->
                                <div class="mt-3">
                                    <label for="profile_picture" class="form-label">Upload New Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture"
                                        accept="image/*" onchange="previewImage(this)">
                                    <div class="form-text">JPG, PNG, or GIF (Max 2MB)</div>

                                    <!-- Remove Button (Centered) -->
                                    <div class="text-center mt-2">
                                        <button type="button" id="removeImageBtn"
                                            class="btn btn-sm btn-outline-danger d-none" onclick="removeImage()">
                                            <i class="fas fa-trash me-1"></i> Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Personal Information Card -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-danger">
                            <h6 class="mb-0 text-white fw-bold">Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="employee_number" class="form-label">Employee ID</label>
                                    <input type="text" name="employee_number" id="employee_number"
                                        class="form-control rounded-2 shadow-sm"
                                        value="{{ old('employee_number', $employee->employee_number) }}" readonly required
                                        style="text-transform: uppercase;">
                                </div>

                                <div class="col-md-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name"
                                        class="form-control rounded-2 shadow-sm @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $employee->personalInfo->first_name) }}"
                                        style="text-transform: uppercase;" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name"
                                        class="form-control rounded-2 shadow-sm"
                                        value="{{ old('middle_name', $employee->personalInfo->middle_name) }}"
                                        style="text-transform: uppercase;">
                                </div>

                                <div class="col-md-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name"
                                        class="form-control rounded-2 shadow-sm @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $employee->personalInfo->last_name) }}"
                                        style="text-transform: uppercase;" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="name_suffix" class="form-label">Name Suffix</label>
                                    <input type="text" name="name_suffix" id="name_suffix"
                                        class="form-control rounded-2 shadow-sm"
                                        value="{{ old('name_suffix', $employee->personalInfo->name_suffix) }}"
                                        style="text-transform: uppercase;">
                                </div>

                                <div class="col-md-3">
                                    <label for="preferred_name" class="form-label">Preferred Name</label>
                                    <input type="text" name="preferred_name" id="preferred_name"
                                        class="form-control rounded-2 shadow-sm"
                                        value="{{ old('preferred_name', $employee->personalInfo->preferred_name) }}"
                                        style="text-transform: uppercase;">
                                </div>

                                <div class="col-md-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select rounded-2 shadow-sm" required>
                                        <option value="Male" {{ (optional($employee->personalInfo)->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ (optional($employee->personalInfo)->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ (optional($employee->personalInfo)->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="birthday" class="form-label">Birthday</label>
                                    <input type="date" name="birthday" id="birthday"
                                        class="form-control rounded-2 shadow-sm @error('birthday') is-invalid @enderror"
                                        value="{{ old('birthday', $employee->personalInfo->birthday ?? '') }}" required>
                                        @error('birthday')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control rounded-2 shadow-sm @error('email') is-invalid @enderror"
                                        value="{{ old('email', $employee->personalInfo->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number"
                                        class="form-control rounded-2 shadow-sm phone-number @error('phone_number') is-invalid @enderror"
                                        value="{{ old('phone_number', $employee->personalInfo->phone_number) }}"
                                        pattern="^(09|\+639)\d{9}$" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="civil_status" class="form-label">Civil Status</label>
                                    <select name="civil_status" id="civil_status" class="form-select rounded-2 shadow-sm"
                                        required>
                                        <option value="Single" {{ ($employee->personalInfo->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ ($employee->personalInfo->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ ($employee->personalInfo->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ ($employee->personalInfo->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ ($employee->personalInfo->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" rows="2" required
                                        class="form-control rounded-2 shadow-sm @error('address') is-invalid @enderror">{{ old('address', $employee->personalInfo->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Government IDs Card -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-danger">
                            <h6 class="mb-0 text-white fw-bold">Government IDs</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="sss_number" class="form-label">SSS Number</label>
                                    <input type="text" name="sss_number" id="sss_number" 
                                        class="form-control rounded-2 shadow-sm @error('sss_number') is-invalid @enderror" 
                                        value="{{ old('sss_number', $employee->governmentId->sss_number ?? '') }}" 
                                        placeholder="Enter SSS number">
                                    @error('sss_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pag_ibig_number" class="form-label">Pag-IBIG Number</label>
                                    <input type="text" name="pag_ibig_number" id="pag_ibig_number" 
                                        class="form-control rounded-2 shadow-sm @error('pag_ibig_number') is-invalid @enderror" 
                                        value="{{ old('pag_ibig_number', $employee->governmentId->pag_ibig_number ?? '') }}" 
                                        placeholder="Enter Pag-IBIG number">
                                    @error('pag_ibig_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="philhealth_number" class="form-label">PhilHealth Number</label>
                                    <input type="text" name="philhealth_number" id="philhealth_number" 
                                        class="form-control rounded-2 shadow-sm @error('philhealth_number') is-invalid @enderror" 
                                        value="{{ old('philhealth_number', $employee->governmentId->philhealth_number ?? '') }}" 
                                        placeholder="Enter PhilHealth number">
                                    @error('philhealth_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tin" class="form-label">TIN</label>
                                    <input type="text" name="tin" id="tin" 
                                        class="form-control rounded-2 shadow-sm @error('tin') is-invalid @enderror" 
                                        value="{{ old('tin', $employee->governmentId->tin ?? '') }}" 
                                        placeholder="Enter Tax Identification Number">
                                    @error('tin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                        <select name="agency_id" id="agency_id" class="form-select rounded-2 shadow-sm"
                                            required>
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}" {{ $agency->id == $employee->agency_id ? 'selected' : '' }}>{{ $agency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="department_id" class="form-label">Department</label>
                                        <select name="department_id" id="department_id"
                                            class="form-select rounded-2 shadow-sm" required>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $department->id == $employee->department_id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cdm_level_id" class="form-label">CDM Level</label>
                                        <select name="cdm_level_id" id="cdm_level_id"
                                            class="form-select rounded-2 shadow-sm" required>
                                            @foreach($cdmLevels as $cdmLevel)
                                                <option value="{{ $cdmLevel->id }}" {{ $cdmLevel->id == $employee->cdm_level_id ? 'selected' : '' }}>{{ $cdmLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="position_id" class="form-label">Position</label>
                                        <select name="position_id" id="position_id" class="form-select rounded-2 shadow-sm"
                                            required>
                                            <option value="">Select Position</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ $position->id == $employee->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hiring_date" class="form-label">Hiring Date</label>
                                        <input type="date" name="hiring_date" id="hiring_date"
                                            class="form-control rounded-2 shadow-sm @error('hiring_date') is-invalid @enderror"
                                            value="{{ old('hiring_date', $employee->hiring_date) }}" required>
                                            @error('hiring_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="employment_status_id" class="form-label">Employment Status</label>
                                        <select name="employment_status_id" id="employment_status_id"
                                            class="form-select rounded-2 shadow-sm">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $status->id == $employee->employment_status_id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="employment_type_id" class="form-label">Employment Type</label>
                                        <select name="employment_type_id" id="employment_type_id"
                                            class="form-select rounded-2 shadow-sm" required>
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
                                        <input type="number" name="basic_pay" id="basic_pay"
                                            class="form-control rounded-2 shadow-sm @error('basic_pay') is-invalid @enderror"
                                            value="{{ optional($employee->compensationPackage)->basic_pay ?? '' }}" required>
                                            @error('basic_pay')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="rata" class="form-label">RATA</label>
                                        <input type="number" name="rata" id="rata" class="form-control rounded-2 shadow-sm"
                                          value="{{ optional($employee->compensationPackage)->rata ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="comm_allowance">Commission Allowance</label>
                                        <input type="number" name="comm_allowance" id="comm_allowance" class="form-control rounded-2 shadow-sm"
                                            value="{{ optional( $employee->compensationPackage)->comm_allowance ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
                                        <input type="number" name="transpo_allowance" id="transpo_allowance"
                                            class="form-control rounded-2 shadow-sm"
                                            value="{{ optional( $employee->compensationPackage)->transpo_allowance ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="parking_toll_allowance" class="form-label">Parking/Toll
                                            Allowance</label>
                                        <input type="number" name="parking_toll_allowance" id="parking_toll_allowance"
                                            class="form-control rounded-2 shadow-sm"
                                            value="{{ optional($employee->compensationPackage)->parking_toll_allowance ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="clothing_allowance" class="form-label">Clothing Allowance</label>
                                        <input type="number" name="clothing_allowance" id="clothing_allowance"
                                            class="form-control rounded-2 shadow-sm"
                                            value="{{ optional($employee->compensationPackage)->clothing_allowance ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="atm_account_number" class="form-label">ATM Account Number</label>
                                        <input type="text" name="atm_account_number" id="atm_account_number"
                                            class="form-control rounded-2 shadow-sm numeric-only"
                                            value="{{ optional($employee->compensationPackage)->atm_account_number ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name"
                                            class="form-control rounded-2 shadow-sm" style="text-transform: uppercase;"
                                            value="{{ optional($employee->compensationPackage)->bank_name ?? '' }}">
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
                        <a href="{{ route('employees.index') }}" class="btn btn-dark me-2 rounded-pill shadow-sm "><i
                                class="fas fa-times"></i> Cancel</a>
                    </div>



            </form>
        </div>
    </div>
    </div>
@endsection
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

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
            backButton.addEventListener('click', function (e) {
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
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const removeBtn = document.getElementById('removeImageBtn');
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                removeBtn.classList.remove('d-none');
            }

            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('images/default-preview.png') }}";
            removeBtn.classList.add('d-none');
        }
    }

    function removeImage() {
        const input = document.getElementById('profile_picture');
        const preview = document.getElementById('imagePreview');
        const removeBtn = document.getElementById('removeImageBtn');

        input.value = '';
        preview.src = "{{ asset('images/default-preview.png') }}";
        removeBtn.classList.add('d-none');
    }

    // Initialize preview with default image on page load
    document.addEventListener('DOMContentLoaded', function () {
        const preview = document.getElementById('imagePreview');
        if (!preview.src) {
            preview.src = "{{ asset('images/default-preview.png') }}";
        }
    });
</script>