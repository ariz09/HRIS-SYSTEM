@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

<div class="container py-4">
    <div class="card shadow border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New Employee</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf

                <!-- Personal Information -->
                <!-- Personal Information -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-light">
        <h6 class="mb-0 text-primary fw-bold">Personal Information</h6>
    </div>
    <div class="card-body row g-3">
    <div class="col-md-3">
        <label for="employee_number" class="form-label">Employee ID</label>
        <input type="text" name="employee_number" id="employee_number" class="form-control rounded-2 shadow-sm" value="{{ $defaultEmployeeNumber }}" readonly>
    </div>
    <div class="col-md-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control rounded-2 shadow-sm" placeholder="Enter first name" required>
    </div>
    <div class="col-md-3">
        <label for="middle_name" class="form-label">Middle Name</label>
        <input type="text" name="middle_name" id="middle_name" class="form-control rounded-2 shadow-sm" placeholder="Enter middle name">
    </div>
    <div class="col-md-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control rounded-2 shadow-sm" placeholder="Enter last name" required>
    </div>
    <div class="col-md-3">
        <label for="name_suffix" class="form-label">Name Suffix</label>
        <input type="text" name="name_suffix" id="name_suffix" class="form-control rounded-2 shadow-sm" placeholder="e.g. Jr., Sr.">
    </div>
    <div class="col-md-3">
        <label for="preferred_name" class="form-label">Preferred Name</label>
        <input type="text" name="preferred_name" id="preferred_name" class="form-control rounded-2 shadow-sm" placeholder="Enter preferred name">
    </div>
    <div class="col-md-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" id="gender" class="form-select rounded-2 shadow-sm">
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="birthday" class="form-label">Birthday</label>
        <input type="date" name="birthday" id="birthday" class="form-control rounded-2 shadow-sm">
    </div>
    <div class="col-md-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control rounded-2 shadow-sm" placeholder="Enter email address">
    </div>
    <div class="col-md-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" class="form-control rounded-2 shadow-sm" placeholder="Enter phone number">
    </div>
    <div class="col-md-3">
        <label for="civil_status" class="form-label">Civil Status</label>
        <select name="civil_status" id="civil_status" class="form-select rounded-2 shadow-sm">
            <option value="">Select status</option>
            <option value="single">Single</option>
            <option value="married">Married</option>
            <option value="separated">Separated</option>
            <option value="widowed">Widowed</option>
        </select>
    </div>
</div>

</div>


                <!-- Employment Information and Compensation in one row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary fw-bold">Employment Information</h6>
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
                                    <input type="date" name="hiring_date" id="hiring_date" class="form-control rounded-2 shadow-sm">
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
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary fw-bold">Compensation Package</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="basic_pay" class="form-label">Basic Pay</label>
                                    <input type="text" name="basic_pay" id="basic_pay" class="form-control rounded-2 shadow-sm" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rata" class="form-label">RATA</label>
                                    <input type="text" name="rata" id="rata" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="comm_allowance" class="form-label">Commission Allowance</label>
                                    <input type="text" name="comm_allowance" id="comm_allowance" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
                                    <input type="text" name="transpo_allowance" id="transpo_allowance" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="parking_toll_allowance" class="form-label">Parking/Toll Allowance</label>
                                    <input type="text" name="parking_toll_allowance" id="parking_toll_allowance" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="clothing_allowance" class="form-label">Clothing Allowance</label>
                                    <input type="text" name="clothing_allowance" id="clothing_allowance" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="atm_account_number" class="form-label">ATM Account Number</label>
                                    <input type="text" name="atm_account_number" id="atm_account_number" class="form-control rounded-2 shadow-sm">
                                </div>
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control rounded-2 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Sections -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#emergencyContactModal">
                        Add Emergency Contact
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#dependentsModal">
                        Add Dependents
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#employmentHistoryModal">
                        Add Employment History
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#educationModal">
                        Add Educational Background
                    </button>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">Create Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Placeholder Modals -->
@foreach ([
    ['emergencyContactModal', 'Emergency Contact'],
    ['dependentsModal', 'Dependents'],
    ['employmentHistoryModal', 'Employment History'],
    ['educationModal', 'Educational Background']
] as [$modalId, $title])
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Form for {{ strtolower($title) }} goes here...</p>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
<script>
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
            
            fetch('/positions?cdm_level_id=' + selectedCDMLevelId)
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
</script>