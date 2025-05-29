@extends('layouts.app') 

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-users me-2"></i>
            My Dependents
        </h4>
        <a href="{{ route('profile.index') }}" 
            class="btn btn-outline-secondary rounded-pill" 
            id="back-button">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <form action="{{ route('profile.dependents.update') }}" method="POST" id="dependentsForm">
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
                <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" style="width: 24px; height: 24px; line-height: 1;">
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
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title" id="deleteConfirmModalLabel">Delete Dependent</h6>
                    <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this dependent?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
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
        // Constants and variables
        let dependentIndex = {{ $dependents->count() }};
        const addDependentBtn = document.getElementById('add-dependent-btn');
        const dependentsContainer = document.getElementById('dependents-container');
        const dependentTemplate = document.getElementById('dependent-template');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const deleteForm = document.getElementById('deleteDependentForm');
        
        // Add new dependent
        addDependentBtn.addEventListener('click', () => {
            const newDependent = dependentTemplate.content.cloneNode(true);
            newDependent.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('__INDEX__', dependentIndex);
            });
            newDependent.querySelector('.dependent-number').textContent = dependentIndex + 1;
            dependentsContainer.appendChild(newDependent);
            dependentIndex++;
            
            // Scroll to new dependent
            dependentsContainer.lastElementChild.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        });

        document.addEventListener('click', e => {
            const deleteBtn = e.target.closest('.delete-dependent-btn');
            if (!deleteBtn) return;
            
            const dependentCard = deleteBtn.closest('.dependent-card');
            const dependentId = dependentCard.querySelector('input[name$="[id]"]')?.value;

            if (dependentId) {
                // Existing dependent - show confirmation modal
                deleteForm.action = `/profile/dependents/${dependentId}`;
                deleteModal.show();
            } else {
                // New dependent - just remove the card without confirmation
                if (document.querySelectorAll('.dependent-card').length <= 1) {
                    showAlert('warning', 'You must maintain at least 1 dependent.');
                    return;
                }
                dependentCard.remove();
                updateDependentNumbers();
                dependentIndex--;
            }
            
            // Prevent default behavior (important for buttons that might still have modal attributes)
            e.preventDefault();
        });

        // Proper modal cleanup when hidden
        const modalElement = document.getElementById('deleteConfirmModal');
        modalElement.addEventListener('hidden.bs.modal', cleanUpModal);

        // Handle delete form submission
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                const deleteBtn = this.querySelector('button[type="submit"]');
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
            });
        }

        // Update dependent numbers when one is deleted
        function updateDependentNumbers() {
            document.querySelectorAll('.dependent-card').forEach((card, index) => {
                const header = card.querySelector('.card-header h6');
                if (header) header.textContent = `Dependent #${index + 1}`;
            });
        }

        // Birthdate validation
        document.addEventListener('change', e => {
            if (e.target.name?.includes('[birthdate]')) {
                const birthdateInput = e.target;
                const selectedDate = new Date(birthdateInput.value);
                const today = new Date();
                
                if (selectedDate > today) {
                    showAlert('warning', 'Birthdate cannot be in the future');
                    birthdateInput.value = '';
                }
            }
        });

        // Phone number formatting
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            input.value = value;
        }

        // Back button validation
        const backButton = document.getElementById('back-button');
        if (backButton) {
            backButton.addEventListener('click', function(e) {
                if (!validateDependents()) {
                    e.preventDefault();
                }
            });
        }

        // Validate dependents before leaving
        function validateDependents() {
            const dependentCards = document.querySelectorAll('.dependent-card');
            
            if (dependentCards.length < 1) {
                showAlert('warning', 'You must provide at least 1 dependent.');
                return false;
            }

            let isValid = true;
            const invalidFields = [];
            
            for (let i = 0; i < dependentCards.length; i++) {
                const card = dependentCards[i];
                const inputs = {
                    fullName: card.querySelector('input[name*="[full_name]"]'),
                    dependentType: card.querySelector('select[name*="[dependent_type]"]'),
                    birthdate: card.querySelector('input[name*="[birthdate]"]')
                };

                if (!inputs.fullName?.value.trim()) invalidFields.push('Full Name');
                if (!inputs.dependentType?.value) invalidFields.push('Relationship');
                if (!inputs.birthdate?.value.trim()) invalidFields.push('Birthdate');
            }

            if (invalidFields.length > 0) {
                showAlert('warning', `Please complete these required fields: ${[...new Set(invalidFields)].join(', ')}`);
                return false;
            }

            return true;
        }
        
        // Clean up modal completely
        function cleanUpModal() {
            // Remove all modal backdrops
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            
            // Reset body styles
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
            document.body.style.overflow = '';
            
            // Ensure modal is hidden
            modalElement.style.display = 'none';
            modalElement.removeAttribute('aria-modal');
            modalElement.removeAttribute('role');
            modalElement.setAttribute('aria-hidden', 'true');
        }
        
        // Show alert message
        function showAlert(type, message) {
            // Remove existing alerts first
            document.querySelectorAll('.global-alert').forEach(el => el.remove());
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `global-alert alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alertDiv.style.zIndex = '1060';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas ${type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }
            }, 5000);
        }
    });
</script>
@endpush