@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Personal Information</h4>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-danger">
            <h6 class="mb-0 text-white fw-bold">Personal Information</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.personal-info.update') }}">
                @csrf
                @method('PUT')
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control rounded-2 shadow-sm @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $personalInfo->first_name) }}"
                            style="text-transform: uppercase;" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name"
                            class="form-control rounded-2 shadow-sm"
                            value="{{ old('middle_name', $personalInfo->middle_name) }}"
                            style="text-transform: uppercase;">
                    </div>

                    <div class="col-md-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control rounded-2 shadow-sm @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $personalInfo->last_name) }}"
                            style="text-transform: uppercase;" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="name_suffix" class="form-label">Name Suffix</label>
                        <input type="text" name="name_suffix" id="name_suffix"
                            class="form-control rounded-2 shadow-sm"
                            value="{{ old('name_suffix', $personalInfo->name_suffix) }}"
                            style="text-transform: uppercase;">
                    </div>

                    <div class="col-md-3">
                        <label for="preferred_name" class="form-label">Preferred Name</label>
                        <input type="text" name="preferred_name" id="preferred_name"
                            class="form-control rounded-2 shadow-sm"
                            value="{{ old('preferred_name', $personalInfo->preferred_name) }}"
                            style="text-transform: uppercase;">
                    </div>

                    <div class="col-md-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select rounded-2 shadow-sm" required>
                            <option value="Male" {{ old('gender', $personalInfo->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $personalInfo->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $personalInfo->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" name="birthday" id="birthday"
                            class="form-control rounded-2 shadow-sm @error('birthday') is-invalid @enderror"
                            value="{{ old('birthday', $personalInfo->birthday ?? '') }}" required>
                            @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded-2 shadow-sm @error('email') is-invalid @enderror"
                            value="{{ old('email', $personalInfo->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number"
                            class="form-control rounded-2 shadow-sm phone-number @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number', $personalInfo->phone_number) }}"
                            pattern="^(09|\+639)\d{9}$" required>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="civil_status" class="form-label">Civil Status</label>
                        <select name="civil_status" id="civil_status" class="form-select rounded-2 shadow-sm"
                            required>
                            <option value="Single" {{ old('civil_status', $personalInfo->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status', $personalInfo->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('civil_status', $personalInfo->civil_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('civil_status', $personalInfo->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ old('civil_status', $personalInfo->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" rows="2" required
                            class="form-control rounded-2 shadow-sm @error('address') is-invalid @enderror">{{ old('address', $personalInfo->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="text-end mt-4 mb-4">

                        <button type="submit" class="btn btn-success rounded-pill shadow-sm px-4">
                            <i class="fas fa-save me-1"></i> Update Employee
                        </button>
                        <a href="{{ route('profile.index') }}" class="btn btn-dark me-2 rounded-pill shadow-sm "><i
                                class="fas fa-times"></i> Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection