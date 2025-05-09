<div class="row">
    <div class="col-md-6 mb-3">
        <label for="agency_id" class="form-label required">Agency</label>
        <select class="form-select @error('agency_id') is-invalid @enderror"
            id="agency_id" name="agency_id" required>
            <option value="">Select Agency</option>
            @foreach($agencies as $agency)
            <option value="{{ $agency->id }}"
                {{ old('agency_id', $employee->agency_id ?? '') == $agency->id ? 'selected' : '' }}>
                {{ $agency->name }}
            </option>
            @endforeach
        </select>
        @error('agency_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="department_id" class="form-label required">Department</label>
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

    <div class="col-md-6 mb-3">
        <label for="cdm_level_id" class="form-label required">CDM Level</label>
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
        <label for="position_id" class="form-label required">Position</label>
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
        <label for="hiring_date" class="form-label required">Hiring Date</label>
        <input type="date" class="form-control @error('hiring_date') is-invalid @enderror"
            id="hiring_date" name="hiring_date"
            value="{{ old('hiring_date', $employee->hiring_date ?? '') }}" required>
        @error('hiring_date')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="employment_status" class="form-label required">Employment Status</label>
        <select class="form-select @error('employment_status_id') is-invalid @enderror"
                id="employment_status_id" name="employment_status_id" required>
            <option value="">Select Status</option>
            @foreach($employmentStatuses as $status)
                <option value="{{ $status->id }}"
                    {{ old('employment_status_id', $employee->employment_status_id ?? '') == $status->id ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
        @error('employment_status')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>