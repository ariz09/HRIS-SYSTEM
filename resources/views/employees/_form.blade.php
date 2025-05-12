@extends('layouts.app')
@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

@php
    $isEdit = isset($employee);
@endphp

<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <h4>{{ $isEdit ? 'Edit Employee' : 'Add New Employee' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ $isEdit ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <!-- Personal Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <x-form.input name="first_name" label="First Name" col="4" required :value="$employee->first_name ?? ''" />
                            <x-form.input name="middle_name" label="Middle Name" col="4" :value="$employee->middle_name ?? ''" />
                            <x-form.input name="last_name" label="Last Name" col="4" required :value="$employee->last_name ?? ''" />
                            <x-form.select name="gender" label="Gender" col="3" :options="['Male','Female','Other']" :selected="$employee->gender ?? ''" />
                            <x-form.input type="date" name="birthday" label="Birthday" col="3" :value="$employee->birthday ?? ''" />
                            <x-form.input type="email" name="email" label="Email" col="3" :value="$employee->email ?? ''" />
                            <x-form.input name="phone_number" label="Phone Number" col="3" :value="$employee->phone_number ?? ''" />
                            <x-form.select name="civil_status" label="Civil Status" col="3" 
                                :options="['Single', 'Married', 'Divorced', 'Widowed', 'Separated']" 
                                :selected="$employee->civil_status ?? ''" />
                        </div>
                    </div>
                </div>

                <!-- Employment + Compensation -->
                <div class="row">
                    <!-- Employment Info -->
                    <div class="col-md-6">
                        <x-card title="Employment Information">
                            <x-form.input name="employee_number" label="Employee Number" :value="$employee->employee_number ?? $defaultEmployeeNumber" readonly />
                            <x-form.input type="date" name="hiring_date" label="Hiring Date" required :value="$employee->hiring_date ?? ''" />
                            <x-form.select name="employment_status_id" label="Employment Status" 
                                :options="$statuses->pluck('name', 'id')" 
                                :selected="$employee->employment_status_id ?? ''" />
                            <x-form.select name="employment_type_id" label="Employment Type" 
                                :options="$employmentTypes->pluck('name', 'id')" 
                                :selected="$employee->employment_type_id ?? ''" />
                            <x-form.select name="agency_id" label="Agency" 
                                :options="$agencies->pluck('name', 'id')" 
                                :selected="$employee->agency_id ?? ''" />
                            <x-form.select name="department_id" label="Department" 
                                :options="$departments->pluck('name', 'id')" 
                                :selected="$employee->department_id ?? ''" />
                            <x-form.select name="cdm_level_id" label="CDM Level" 
                                :options="$cdmLevels->pluck('name', 'id')" 
                                :selected="$employee->cdm_level_id ?? ''" />
                            <div class="form-group mb-3">
                                <label for="position_id">Position</label>
                                <select name="position_id" id="position_id" class="form-control">
                                    <option value="">Select Position</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}" 
                                            data-cdm-level="{{ $position->cdm_level_id }}" 
                                            {{ (isset($employee) && $employee->position_id == $position->id) ? 'selected' : '' }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </x-card>
                    </div>

                    <!-- Compensation Info -->
                    <div class="col-md-6">
                        <x-card title="Compensation Package">
                            <x-form.input type="number" name="basic_pay" label="Basic Pay" required :value="$employee->basic_pay ?? ''" />
                            <x-form.input type="number" name="rata" label="RATA" :value="$employee->rata ?? ''" />
                            <x-form.input type="number" name="comm_allowance" label="Commission Allowance" :value="$employee->comm_allowance ?? ''" />
                            <x-form.input type="number" name="transpo_allowance" label="Transportation Allowance" :value="$employee->transpo_allowance ?? ''" />
                            <x-form.input type="number" name="parking_toll_allowance" label="Parking/Toll Allowance" :value="$employee->parking_toll_allowance ?? ''" />
                            <x-form.input type="number" name="clothing_allowance" label="Clothing Allowance" :value="$employee->clothing_allowance ?? ''" />
                            <x-form.input name="atm_account_number" label="ATM Account Number" :value="$employee->atm_account_number ?? ''" />
                            <x-form.input name="bank_name" label="Bank Name" :value="$employee->bank_name ?? ''" />
                        </x-card>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? 'Update Employee' : 'Create Employee' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('employees._position_filter_script')
@endsection
