<div class="row">
    <div class="col-md-6 mb-3">
        <label for="employee_number" class="form-label required">Employee Number</label>
        <input type="text" class="form-control @error('employee_number') is-invalid @enderror"
            id="employee_number" name="employee_number"
            value="{{ old('employee_number', $employee->employee_number ?? $formattedEmployeeNumber ?? '') }}"
            required readonly>
        @error('employee_number')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="first_name" class="form-label required">First Name</label>
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
        <label for="last_name" class="form-label required">Last Name</label>
        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
            id="last_name" name="last_name"
            value="{{ old('last_name', $employee->last_name ?? '') }}" required>
        @error('last_name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="gender" class="form-labe requiredl">Gender</label>
        <select class="form-select" id="gender" name="gender" required>
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
        <label for="civil_status" class="form-label required">Civil Status</label>
        <select class="form-select" id="civil_status" name="civil_status" required>
            <option value="">Select Status</option>
            <option value="single" {{ old('civil_status', $employee->civil_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
            <option value="married" {{ old('civil_status', $employee->civil_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
            <option value="widowed" {{ old('civil_status', $employee->civil_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
            <option value="divorced" {{ old('civil_status', $employee->civil_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
        </select>
    </div>
</div>