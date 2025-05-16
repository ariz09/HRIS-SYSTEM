@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mt-4">
            <i class="fas fa-users me-2"></i>
            Dependents for {{ $employeeName }}
        </h3>
        <a href="{{ route('employees.edit', $employee) }}" 
            class="btn btn-outline-secondary rounded-pill" 
            id="back-button">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    <form action="{{ route('employees.dependents.update', $employee) }}" method="POST" id="dependentsForm">
        @csrf
        @method('PUT')

        <div id="dependents-container" class="row g-4">
            @foreach($dependents as $index => $dependent)
                @include('employees.partials.dependent-card', ['index' => $index, 'dependent' => $dependent])
            @endforeach
        </div>

        @if($dependents->count() < 1)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                <div>You must provide at least 1 dependent.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between mt-1">
            <button type="button" id="add-dependent-btn" class="btn btn-outline-primary rounded-pill mb-3">
                <i class="fas fa-plus me-1"></i> Add Dependent
            </button>

            <button type="submit" class="btn btn-success rounded-pill mb-3">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Dependent Card Template -->
<template id="dependent-template">
    <div class="dependent-card col-md-6">
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
                <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                    Dependent #<span class="dependent-number"></span>
                </h6>
                <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            <div class="card-body">
                <input type="hidden" name="dependents[__INDEX__][id]" value="">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="dependents[__INDEX__][full_name]" class="form-control uppercase" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <select name="dependents[__INDEX__][dependent_type]" class="form-select" required>
                            <option value="">Select Relationship</option>
                            @foreach($dependentTypeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Birthdate <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" 
                            name="dependents[__INDEX__][birthdate]" 
                            class="form-control" 
                            required
                            max="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" 
                            name="dependents[__INDEX__][contact_number]" 
                            class="form-control" 
                            pattern="^(09|\+639)\d{9}$"
                            title="Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)"
                            oninput="formatPhoneNumber(this)">
                    </div>
                    <div class="invalid-feedback">
                        Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteDependentForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Dependent</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this dependent?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let dependentIndex = {{ $dependents->count() }};
        let targetToDelete = null;

        const addDependentBtn = document.getElementById('add-dependent-btn');
        const dependentsContainer = document.getElementById('dependents-container');
        const dependentTemplate = document.getElementById('dependent-template');

        addDependentBtn.addEventListener('click', () => {
            const newDependent = dependentTemplate.content.cloneNode(true);
            newDependent.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('__INDEX__', dependentIndex);
            });

            newDependent.querySelector('.dependent-number').textContent = dependentIndex + 1;
            dependentsContainer.appendChild(newDependent);
            dependentIndex++;
        });

        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteForm = document.getElementById('deleteDependentForm');

        document.addEventListener('click', e => {
            const deleteBtn = e.target.closest('.delete-dependent-btn');
            if (deleteBtn) {
                const dependentCard = deleteBtn.closest('.dependent-card');
                const dependentId = dependentCard.querySelector('input[name$="[id]"]')?.value;

                if (dependentId) {
                    deleteForm.action = `/employees/${@json($employee->employee_number)}/dependents/${dependentId}`;
                } else {
                    deleteForm.action = '';
                    deleteForm.onsubmit = function(e) {
                        e.preventDefault();
                        dependentCard.remove();
                        const modal = bootstrap.Modal.getInstance(deleteModal);
                        modal.hide();
                        return false;
                    };
                }
            }
        });

        const backButton = document.getElementById('back-button');
        backButton.addEventListener('click', function(e) {
            const dependentCards = document.querySelectorAll('.dependent-card');

            if (dependentCards.length < 1) {
                e.preventDefault();
                showAlert('warning', 'You must provide at least 1 dependent before going back.');
                return;
            }

            let isValid = true;
            for (let i = 0; i < 1; i++) {
                const card = dependentCards[i];
                const fullName = card.querySelector('input[name*="[full_name]"]');
                const dependentType = card.querySelector('select[name*="[dependent_type]"]');
                const birthdate = card.querySelector('input[name*="[birthdate]"]');

                if (!fullName?.value.trim() || !dependentType?.value || !birthdate?.value.trim()) {
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();
                showAlert('warning', 'Please fill out Full Name, Relationship, and Birthdate for at least one dependent before going back.');
            }
        });
    });
    </script>
@endpush