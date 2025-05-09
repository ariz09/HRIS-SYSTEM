@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee->id) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Emergency Contacts</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-phone-alt me-1"></i> Emergency Contacts
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.emergency', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div id="emergency-container">
                    @forelse(old('emergency_contacts', $employee->emergencyContacts->toArray()) as $index => $contact)
                        <div class="emergency-item card mb-3">
                            <div class="card-body">
                                @if($loop->index > 0)
                                    <button type="button" class="btn-close float-end remove-emergency" aria-label="Close"></button>
                                @endif
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" name="emergency_contacts[{{ $index }}][name]" class="form-control" 
                                               value="{{ $contact['name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Relationship</label>
                                        <select name="emergency_contacts[{{ $index }}][relationship]" class="form-select" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Spouse" {{ ($contact['relationship'] ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                            <option value="Parent" {{ ($contact['relationship'] ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                                            <option value="Child" {{ ($contact['relationship'] ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
                                            <option value="Sibling" {{ ($contact['relationship'] ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                            <option value="Friend" {{ ($contact['relationship'] ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                            <option value="Other" {{ ($contact['relationship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Contact Number</label>
                                        <input type="text" name="emergency_contacts[{{ $index }}][phone]" class="form-control" 
                                               value="{{ $contact['phone'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Address</label>
                                        <textarea name="emergency_contacts[{{ $index }}][address]" class="form-control" required>{{ $contact['address'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="emergency-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" name="emergency_contacts[0][name]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Relationship</label>
                                        <select name="emergency_contacts[0][relationship]" class="form-select" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Child">Child</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Friend">Friend</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Contact Number</label>
                                        <input type="text" name="emergency_contacts[0][phone]" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Address</label>
                                        <textarea name="emergency_contacts[0][address]" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mb-3">
                    <button type="button" id="add-emergency" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Another Emergency Contact
                    </button>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Emergency Contacts
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let emergencyIndex = {{ count(old('emergency_contacts', $employee->emergencyContacts->toArray())) }};
    
    // Add new emergency contact item
    document.getElementById('add-emergency').addEventListener('click', function() {
        const container = document.getElementById('emergency-container');
        const newItem = document.createElement('div');
        newItem.className = 'emergency-item card mb-3';
        newItem.innerHTML = `
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-emergency" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Full Name</label>
                        <input type="text" name="emergency_contacts[${emergencyIndex}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Relationship</label>
                        <select name="emergency_contacts[${emergencyIndex}][relationship]" class="form-select" required>
                            <option value="">Select Relationship</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Parent">Parent</option>
                            <option value="Child">Child</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Friend">Friend</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Contact Number</label>
                        <input type="text" name="emergency_contacts[${emergencyIndex}][phone]" class="form-control" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label required">Address</label>
                        <textarea name="emergency_contacts[${emergencyIndex}][address]" class="form-control" required></textarea>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        emergencyIndex++;
    });

    // Remove emergency contact item
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-emergency')) {
            if (document.querySelectorAll('.emergency-item').length > 1) {
                e.target.closest('.emergency-item').remove();
            } else {
                alert('At least one emergency contact is required.');
            }
        }
    });
});
</script>
@endpush
@endsection