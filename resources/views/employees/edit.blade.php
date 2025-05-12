@extends('layouts.app')
@section('content')
    <x-success-alert :message="session('success')" />
    <x-error-alert :message="session('error')" />

    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- This tells Laravel to use the PUT method for updating -->

                    <!-- Personal Information Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                        value="{{ old('first_name', $employee->personalInfo->first_name) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control"
                                        value="{{ old('middle_name', $employee->personalInfo->middle_name) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                        value="{{ old('last_name', $employee->personalInfo->last_name) }}" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="Male" {{ $employee->personalInfo->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $employee->personalInfo->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $employee->personalInfo->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="birthday">Birthday</label>
                                    <input type="date" name="birthday" id="birthday" class="form-control"
                                        value="{{ old('birthday', $employee->personalInfo->birthday ?? '') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', $employee->personalInfo->email) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                                        value="{{ old('phone_number', $employee->personalInfo->phone_number) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="civil_status">Civil Status</label>
                                    <select name="civil_status" id="civil_status" class="form-control">
                                        <option value="Single" {{ ($employee->personalInfo->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ ($employee->personalInfo->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ ($employee->personalInfo->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ ($employee->personalInfo->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ ($employee->personalInfo->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment + Compensation in One Row -->
                    <div class="row">
                        <!-- Employment Information Card -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Employment Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label>Employee Number</label>
                                        <input type="text" class="form-control" value="{{ $employee->employee_number }}"
                                            disabled>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="hiring_date">Hiring Date</label>
                                        <input type="date" name="hiring_date" id="hiring_date" class="form-control"
                                            value="{{ old('hiring_date', $employee->hiring_date) }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="employment_status_id">Employment Status</label>
                                        <select name="employment_status_id" id="employment_status_id" class="form-control">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $status->id == $employee->employment_status_id ? 'selected' : '' }}>
                                                    {{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="employment_type_id">Employment Type</label>
                                        <select name="employment_type_id" id="employment_type_id" class="form-control">
                                            @foreach($employmentTypes as $type)
                                                <option value="{{ $type->id }}" {{ $type->id == $employee->employment_type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="agency_id">Agency</label>
                                        <select name="agency_id" id="agency_id" class="form-control">
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}" {{ $agency->id == $employee->agency_id ? 'selected' : '' }}>{{ $agency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="department_id">Department</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $department->id == $employee->department_id ? 'selected' : '' }}>
                                                    {{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cdm_level_id">CDM Level</label>
                                        <select name="cdm_level_id" id="cdm_level_id" class="form-control">
                                            @foreach($cdmLevels as $cdmLevel)
                                                <option value="{{ $cdmLevel->id }}" {{ $cdmLevel->id == $employee->cdm_level_id ? 'selected' : '' }}>{{ $cdmLevel->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="position_id">Position</label>
                                        <select name="position_id" id="position_id" class="form-control" required>
                                            <option value="">Select Position</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ $position->id == $employee->position_id ? 'selected' : '' }}>{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compensation Package Card -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Compensation Package</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="basic_pay">Basic Pay</label>
                                        <input type="number" name="basic_pay" id="basic_pay" class="form-control"
                                            value="{{ old('basic_pay', $employee->compensationPackage->basic_pay) }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="rata">RATA</label>
                                        <input type="number" name="rata" id="rata" class="form-control"
                                            value="{{ old('rata', $employee->compensationPackage->rata) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="comm_allowance">Commission Allowance</label>
                                        <input type="number" name="comm_allowance" id="comm_allowance" class="form-control"
                                            value="{{ old('comm_allowance', $employee->compensationPackage->comm_allowance) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="transpo_allowance">Transportation Allowance</label>
                                        <input type="number" name="transpo_allowance" id="transpo_allowance"
                                            class="form-control"
                                            value="{{ old('transpo_allowance', $employee->compensationPackage->transpo_allowance) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="parking_toll_allowance">Parking/Toll Allowance</label>
                                        <input type="number" name="parking_toll_allowance" id="parking_toll_allowance"
                                            class="form-control"
                                            value="{{ old('parking_toll_allowance', $employee->compensationPackage->parking_toll_allowance) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="clothing_allowance">Clothing Allowance</label>
                                        <input type="number" name="clothing_allowance" id="clothing_allowance"
                                            class="form-control"
                                            value="{{ old('clothing_allowance', $employee->compensationPackage->clothing_allowance) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="atm_account_number">ATM Account Number</label>
                                        <input type="text" name="atm_account_number" id="atm_account_number"
                                            class="form-control"
                                            value="{{ old('atm_account_number', $employee->compensationPackage->atm_account_number) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="bank_name">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control"
                                            value="{{ old('bank_name', $employee->compensationPackage->bank_name) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cdmSelect = document.getElementById('cdm_level_id');
    const positionSelect = document.getElementById('position_id');
    const selectedPositionId = "{{ $employee->position_id }}"; // Preserve selected position

    if (cdmSelect) {
        cdmSelect.addEventListener('change', function () {
            const selectedCDMLevelId = this.value;

            positionSelect.innerHTML = '<option value="">Loading positions...</option>';

            fetch('/positions?cdm_level_id=' + selectedCDMLevelId)
                .then(response => response.json())
                .then(data => {
                    positionSelect.innerHTML = '<option value="">Select Position</option>';

                    data.positions.forEach(function (position) {
                        const option = new Option(position.name, position.id);
                        if (position.id == selectedPositionId) {
                            option.selected = true; // Re-select
                        }
                        positionSelect.add(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching positions:', error);
                    positionSelect.innerHTML = '<option value="">Error loading positions</option>';
                });
        });

        // Trigger initial load
        if (cdmSelect.value) {
            cdmSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
