@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h4>Add New Employee</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf

                <!-- Personal Information Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" id="middle_name" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="birthday">Birthday</label>
                                <input type="date" name="birthday" id="birthday" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="civil_status">Civil Status</label>
                                <select name="civil_status" id="civil_status" class="form-control">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
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
                                    <label for="employee_number">Employee Number</label>
                                    <input type="text" name="employee_number" id="employee_number" class="form-control" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="hiring_date">Hiring Date</label>
                                    <input type="date" name="hiring_date" id="hiring_date" class="form-control" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="employment_status_id">Employment Status</label>
                                    <select name="employment_status_id" id="employment_status_id" class="form-control">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="employment_type_id">Employment Type</label>
                                    <select name="employment_type_id" id="employment_type_id" class="form-control">
                                        @foreach($employmentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="agency_id">Agency</label>
                                    <select name="agency_id" id="agency_id" class="form-control">
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="position_id">Position</label>
                                    <select name="position_id" id="position_id" class="form-control">
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}">{{ $position->name }}</option>
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
                                    <input type="number" name="basic_pay" id="basic_pay" class="form-control" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="rata">RATA</label>
                                    <input type="number" name="rata" id="rata" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="comm_allowance">Commission Allowance</label>
                                    <input type="number" name="comm_allowance" id="comm_allowance" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="transpo_allowance">Transportation Allowance</label>
                                    <input type="number" name="transpo_allowance" id="transpo_allowance" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="parking_toll_allowance">Parking/Toll Allowance</label>
                                    <input type="number" name="parking_toll_allowance" id="parking_toll_allowance" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="clothing_allowance">Clothing Allowance</label>
                                    <input type="number" name="clothing_allowance" id="clothing_allowance" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="atm_account_number">ATM Account Number</label>
                                    <input type="text" name="atm_account_number" id="atm_account_number" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
