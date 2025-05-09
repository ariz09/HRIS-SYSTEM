@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee->id) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Dependents</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-users me-1"></i> Dependents
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.dependents', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div id="dependents-container">
                    @forelse(old('dependents', $employee->dependents->toArray()) as $index => $dependent)
                        <div class="dependent-item card mb-3">
                            <div class="card-body">
                                @if($loop->index > 0)
                                    <button type="button" class="btn-close float-end remove-dependent" aria-label="Close"></button>
                                @endif
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" name="dependents[{{ $index }}][name]" class="form-control" 
                                               value="{{ $dependent['name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Relationship</label>
                                        <select name="dependents[{{ $index }}][relationship]" class="form-select" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Spouse" {{ ($dependent['relationship'] ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                            <option value="Child" {{ ($dependent['relationship'] ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
                                            <option value="Parent" {{ ($dependent['relationship'] ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                            <option value="Sibling" {{ ($dependent['relationship'] ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                            <option value="Other" {{ ($dependent['relationship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Birthdate</label>
                                        <input type="date" name="dependents[{{ $index }}][birthdate]" class="form-control" 
                                               value="{{ $dependent['birthdate'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="dependents[{{ $index }}][contact_number]" class="form-control" 
                                               value="{{ $dependent['contact_number'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Occupation</label>
                                        <input type="text" name="dependents[{{ $index }}][occupation]" class="form-control" 
                                               value="{{ $dependent['occupation'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dependent-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" name="dependents[0][name]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Relationship</label>
                                        <select name="dependents[0][relationship]" class="form-select" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Child">Child</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Birthdate</label>
                                        <input type="date" name="dependents[0][birthdate]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="dependents[0][contact_number]" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Occupation</label>
                                        <input type="text" name="dependents[0][occupation]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mb-3">
                    <button type="button" id="add-dependent" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Another Dependent
                    </button>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Dependents
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let dependentIndex = {{ count(old('dependents', $employee->dependents->toArray())) }};
    
    // Add new dependent item
    document.getElementById('add-dependent').addEventListener('click', function() {
        const container = document.getElementById('dependents-container');
        const newItem = document.createElement('div');
        newItem.className = 'dependent-item card mb-3';
        newItem.innerHTML = `
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-dependent" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Full Name</label>
                        <input type="text" name="dependents[${dependentIndex}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Relationship</label>
                        <select name="dependents[${dependentIndex}][relationship]" class="form-select" required>
                            <option value="">Select Relationship</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                            <option value="Parent">Parent</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Birthdate</label>
                        <input type="date" name="dependents[${dependentIndex}][birthdate]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="dependents[${dependentIndex}][contact_number]" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="dependents[${dependentIndex}][occupation]" class="form-control">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        dependentIndex++;
    });

    // Remove dependent item
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-dependent')) {
            if (document.querySelectorAll('.dependent-item').length > 1) {
                e.target.closest('.dependent-item').remove();
            } else {
                alert('At least one dependent entry is required.');
            }
        }
    });
});
</script>
@endpush
@endsection