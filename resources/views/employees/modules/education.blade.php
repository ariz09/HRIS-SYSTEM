@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee->id) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Education</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-graduation-cap me-1"></i> Education
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.education', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div id="education-container">
                    @forelse(old('educations', $employee->educations->toArray()) as $index => $education)
                        <div class="education-item card mb-3">
                            <div class="card-body">
                                @if($loop->index > 0)
                                    <button type="button" class="btn-close float-end remove-education" aria-label="Close"></button>
                                @endif
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Level</label>
                                        <select name="educations[{{ $index }}][level]" class="form-select" required>
                                            <option value="">Select Level</option>
                                            <option value="Elementary" {{ ($education['level'] ?? '') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                            <option value="High School" {{ ($education['level'] ?? '') == 'High School' ? 'selected' : '' }}>High School</option>
                                            <option value="Vocational" {{ ($education['level'] ?? '') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                            <option value="College" {{ ($education['level'] ?? '') == 'College' ? 'selected' : '' }}>College</option>
                                            <option value="Post Graduate" {{ ($education['level'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">School Name</label>
                                        <input type="text" name="educations[{{ $index }}][school_name]" class="form-control" 
                                               value="{{ $education['school_name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Degree/Course</label>
                                        <input type="text" name="educations[{{ $index }}][degree]" class="form-control" 
                                               value="{{ $education['degree'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Started</label>
                                        <select name="educations[{{ $index }}][year_started]" class="form-select">
                                            <option value="">Select Year</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ ($education['year_started'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Graduated</label>
                                        <select name="educations[{{ $index }}][year_graduated]" class="form-select">
                                            <option value="">Select Year</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ ($education['year_graduated'] ?? '') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Honors/Awards</label>
                                        <textarea name="educations[{{ $index }}][honors]" class="form-control">{{ $education['honors'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="education-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Level</label>
                                        <select name="educations[0][level]" class="form-select" required>
                                            <option value="">Select Level</option>
                                            <option value="Elementary">Elementary</option>
                                            <option value="High School">High School</option>
                                            <option value="Vocational">Vocational</option>
                                            <option value="College">College</option>
                                            <option value="Post Graduate">Post Graduate</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">School Name</label>
                                        <input type="text" name="educations[0][school_name]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Degree/Course</label>
                                        <input type="text" name="educations[0][degree]" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Started</label>
                                        <select name="educations[0][year_started]" class="form-select">
                                            <option value="">Select Year</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Year Graduated</label>
                                        <select name="educations[0][year_graduated]" class="form-select">
                                            <option value="">Select Year</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Honors/Awards</label>
                                        <textarea name="educations[0][honors]" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mb-3">
                    <button type="button" id="add-education" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Another Education
                    </button>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Education
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let educationIndex = {{ count(old('educations', $employee->educations->toArray())) }};
    
    // Add new education item
    document.getElementById('add-education').addEventListener('click', function() {
        const container = document.getElementById('education-container');
        const newItem = document.createElement('div');
        newItem.className = 'education-item card mb-3';
        newItem.innerHTML = `
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-education" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Level</label>
                        <select name="educations[${educationIndex}][level]" class="form-select" required>
                            <option value="">Select Level</option>
                            <option value="Elementary">Elementary</option>
                            <option value="High School">High School</option>
                            <option value="Vocational">Vocational</option>
                            <option value="College">College</option>
                            <option value="Post Graduate">Post Graduate</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">School Name</label>
                        <input type="text" name="educations[${educationIndex}][school_name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Degree/Course</label>
                        <input type="text" name="educations[${educationIndex}][degree]" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year Started</label>
                        <select name="educations[${educationIndex}][year_started]" class="form-select">
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Year Graduated</label>
                        <select name="educations[${educationIndex}][year_graduated]" class="form-select">
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Honors/Awards</label>
                        <textarea name="educations[${educationIndex}][honors]" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        educationIndex++;
    });

    // Remove education item
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-education')) {
            if (document.querySelectorAll('.education-item').length > 1) {
                e.target.closest('.education-item').remove();
            } else {
                alert('At least one education entry is required.');
            }
        }
    });
});
</script>
@endpush
@endsection