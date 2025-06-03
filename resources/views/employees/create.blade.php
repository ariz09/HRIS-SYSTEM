@extends('layouts.app')

<style>/* Profile Picture Styles */
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
        <li class="breadcrumb-item active">Add New Employee</li>
    </ol>
        <div class="card-body">
            <form novalidate action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-danger text-white fw-bold">
                                <i class="fas fa-camera me-1"></i> Profile Picture
                            </div>
                            <div class="card-body d-flex flex-column align-items-center">
                                <!-- Default Profile Picture -->
                                <div class="mb-4 text-center">
                                    <div class="profile-picture-container mb-3">
                                        <img id="currentProfilePic" src="{{ asset('images/default-user.png') }}" 
                                            alt="Default Profile Picture" class="img-thumbnail profile-picture">
                                    </div>
                                    <div class="text-muted small">Profile Picture</div>
                                </div>
                                
                                <!-- Upload Controls -->
                                <div class="w-100">
                                    <label for="profile_picture" class="form-label">Upload Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                                        accept="image/*" onchange="previewImage(this)">
                                    <div class="form-text">JPG, PNG, or GIF (Max 2MB)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-danger">
                        <h6 class="mb-0 text-white fw-bold">Personal Information</h6>
                    </div>
                    <div class="card-body row g-3">
                    <div class="col-md-3">
                        <label for="employee_number" class="form-label">Employee ID</label>
                        <input type="text" name="employee_number" id="employee_number" class="form-control rounded-2 shadow-sm" value="{{ $defaultEmployeeNumber }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name" 
                            class="form-control rounded-2 shadow-sm @error('first_name') is-invalid @enderror" 
                            value="{{ old('first_name') }}" placeholder="Enter first name" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror  
                    </div>
                    <div class="col-md-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" 
                            class="form-control rounded-2 shadow-sm"
                            value="{{ old('middle_name') }}" placeholder="Enter middle name">                
                    </div>
                    <div class="col-md-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name" 
                         class="form-control rounded-2 shadow-sm @error('last_name') is-invalid @enderror" 
                            value="{{ old('last_name') }}" placeholder="Enter last name" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror    
                    </div>
                    <div class="col-md-3">
                        <label for="name_suffix" class="form-label">Name Suffix</label>
                        <input type="text" name="name_suffix" id="name_suffix"  
                        class="form-control rounded-2 shadow-sm"
                            value="{{ old('name_suffix') }}" placeholder="Enter name suffix" >
                    </div>
                    <div class="col-md-3">
                        <label for="preferred_name" class="form-label">Preferred Name</label>
                        <input type="text" name="preferred_name" id="preferred_name" 
                            class="form-control rounded-2 shadow-sm" 
                            value="{{ old('preferred_name') }}" placeholder="Enter preffered name">
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select rounded-2 shadow-sm @error('gender') is-invalid @enderror" required>
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror    
                    </div>
                    <div class="col-md-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" name="birthday" id="birthday" 
                            class="form-control rounded-2 shadow-sm @error('birthday') is-invalid @enderror" 
                            value="{{ old('birthday') }}" placeholder="Enter birthdate" required>
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror  
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" 
                            class="form-control rounded-2 shadow-sm @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" placeholder="Enter email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror 
                    </div>
                    <div class="col-md-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" 
                            class="form-control rounded-2 shadow-sm @error('phone_number') is-invalid @enderror" 
                            value="{{ old('phone_number') }}" placeholder="Enter phone number" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror 
                    </div>
                    <div class="col-md-3">
                        <label for="civil_status" class="form-label">Civil Status</label>
                        <select name="civil_status" id="civil_status" class="form-select rounded-2 shadow-sm @error('civil_status') is-invalid @enderror"  required>
                            <option value="">Select status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Separated">Separated</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                        @error('civil_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror 
                    </div>
                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" rows="2" 
                            class="form-control rounded-2 shadow-sm @error('address') is-invalid @enderror" 
                            value="{{ old('address') }}" placeholder="Enter address" required> </textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror 
                    </div>
                </div>

                </div>


                <!-- Employment Information and Compensation in one row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-danger">
                                <h6 class="mb-0 text-white fw-bold">Employment Information</h6>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="agency_id" class="form-label">Agency</label>
                                    <select name="agency_id" id="agency_id" class="form-select rounded-2 shadow-sm">
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-select rounded-2 shadow-sm">
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="cdm_level_id" class="form-label">CDM Level</label>
                                    <select name="cdm_level_id" id="cdm_level_id" class="form-select rounded-2 shadow-sm">
                                        @foreach($cdmLevels as $cdmLevel)
                                            <option value="{{ $cdmLevel->id }}">{{ $cdmLevel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Position</label>
                                    <select name="position_id" id="position_id" class="form-select rounded-2 shadow-sm">
                                        <option value="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" data-cdm-level="{{ $position->cdm_level_id }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                        <div class="mb-3">
                                    <label for="hiring_date" class="form-label">Hiring Date</label>
                                    <input type="date" name="hiring_date" id="hiring_date" 
                                        class="form-control rounded-2 shadow-sm @error('hiring_date') is-invalid @enderror" 
                                        value="{{ old('hiring_date') }}" placeholder="Enter hiring date" required> 
                                    @error('hiring_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror 
                                </div>
                                <div class="mb-3">
                                    <label for="employment_status_id" class="form-label">Employment Status</label>
                                    <select name="employment_status_id" id="employment_status_id" class="form-select rounded-2 shadow-sm">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="employment_type_id" class="form-label">Employment Type</label>
                                    <select name="employment_type_id" id="employment_type_id" class="form-select rounded-2 shadow-sm">
                                        @foreach($employmentTypes as $employmentType)
                                            <option value="{{ $employmentType->id }}">{{ $employmentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-danger">
                                <h6 class="mb-0 text-white fw-bold">Compensation Package</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="basic_pay" class="form-label">Basic Pay</label>
                                    <input type="text" name="basic_pay" id="basic_pay" 
                                          class="form-control rounded-2 shadow-sm @error('basic_pay') is-invalid @enderror" 
                                        value="{{ old('basic_pay') }}" placeholder="Enter basic pay" required> 
                                    @error('basic_pay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror 
                                </div>
                                <div class="mb-3">
                                    <label for="rata" class="form-label">RATA</label>
                                    <input type="text" name="rata" id="rata" 
                                        class="form-control rounded-2 shadow-sm"
                                        value="{{ old('rata') }}" placeholder="Enter rata"> 
                                </div>
                                <div class="mb-3">
                                    <label for="comm_allowance" class="form-label">Communication Allowance</label>
                                    <input type="text" name="comm_allowance" id="comm_allowance" 
                                       class="form-control rounded-2 shadow-sm"
                                        value="{{ old('comm_allowance') }}" placeholder="Enter commision allowance"> 
                                </div>
                                <div class="mb-3">
                                    <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
                                    <input type="text" name="transpo_allowance" id="transpo_allowance" 
                                       class="form-control rounded-2 shadow-sm"
                                        value="{{ old('transpo_allowance') }}" placeholder="Enter transportation allowance"> 
                                </div>
                                <div class="mb-3">
                                    <label for="parking_toll_allowance" class="form-label">Parking/Toll Allowance</label>
                                    <input type="text" name="parking_toll_allowance" id="parking_toll_allowance" 
                                       class="form-control rounded-2 shadow-sm"
                                        value="{{ old('parking_toll_allowance') }}" placeholder="Enter parking toll allowance"> 
                                </div>
                                <div class="mb-3">
                                    <label for="clothing_allowance" class="form-label">Clothing Allowance</label>
                                    <input type="text" name="clothing_allowance" id="clothing_allowance" 
                                       class="form-control rounded-2 shadow-sm" 
                                        value="{{ old('clothing_allowance') }}" placeholder="Enter clothing allowance"> 
                                </div>
                                <div class="mb-3">
                                    <label for="atm_account_number" class="form-label">ATM Account Number</label>
                                    <input type="text" name="atm_account_number" id="atm_account_number" 
                                       class="form-control rounded-2 shadow-sm"
                                        value="{{ old('atm_account_number') }}" placeholder="Enter ATM Account Number"> 
                                </div>
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" id="bank_name" 
                                       class="form-control rounded-2 shadow-sm"
                                        value="{{ old('bank_name') }}" placeholder="Enter Bank name"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                    <div class="text-end mt-4 mb-4">
                        
                        <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4">
                            <i class="fas fa-save me-1"></i> Create Employee
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-dark me-2 rounded-pill shadow-sm "><i class="fas fa-times"></i> Cancel</a>
                    </div>
            </form>
        </div>
    </div>
</div>



@endsection
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    // Image preview function
    function previewImage(input) {
        const preview = document.getElementById('currentProfilePic');
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Retain form data on page refresh
    window.addEventListener('beforeunload', function() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        
        // Store form data in sessionStorage
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });
        
        sessionStorage.setItem('employeeFormData', JSON.stringify(formObject));
    });

    // Load stored form data
    const savedFormData = sessionStorage.getItem('employeeFormData');
    if (savedFormData) {
        const formData = JSON.parse(savedFormData);
        for (const [key, value] of Object.entries(formData)) {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'file') continue; // Can't set file inputs
                input.value = value;
                
                // For select elements
                if (input.tagName === 'SELECT') {
                    const option = input.querySelector(`option[value="${value}"]`);
                    if (option) option.selected = true;
                }
            }
        }
        sessionStorage.removeItem('employeeFormData');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // CDM level change handler
    const cdmSelect = document.getElementById('cdm_level_id');
    if (cdmSelect) { // Check if element exists
        cdmSelect.addEventListener('change', function () {
            const selectedCDMLevelId = this.value;
            const positionSelect = document.getElementById('position_id');
            
            // Reset to default option immediately
            positionSelect.innerHTML = '<option value="">Select Position</option>';
            positionSelect.value = '';
            
            if (!selectedCDMLevelId) return;
            
            // Show loading state
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
                        positionSelect.add(option);
                    });
                    positionSelect.value = '';
                })
                .catch(error => {
                    console.error('Error fetching positions:', error);
                    positionSelect.innerHTML = '<option value="">Error loading positions</option>';
                });
        });

        // Trigger initial load if value exists
        if (cdmSelect.value) {
            cdmSelect.dispatchEvent(new Event('change'));
        }
    } else {
        console.error('CDM Level select element not found');
    }
});


function previewImage(input) {
    const preview = document.getElementById('currentProfilePic');
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(file);
    }
}



</script>