@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-graduation-cap me-2"></i>
            My Education Records
        </h4>
        <a href="{{ route('profile.index') }}" 
            class="btn btn-outline-secondary rounded-pill" 
            id="back-button">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <form action="{{ route('profile.educations.update') }}" method="POST" id="educationsForm">
        @csrf
        @method('PUT')

        <div id="educations-container" class="row g-4">
            @foreach($educations as $index => $education)
                <div class="education-card col-md-6">
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 text-white">
                                Education Record #{{ $index + 1 }}
                            </h6>
                            @if($education->id)
                                <button type="button" class="btn btn-sm btn-light delete-education-btn text-danger rounded-circle" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-education-id="{{ $education->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-light delete-education-btn text-danger rounded-circle">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($education->id)
                                <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $education->id }}">
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                    <input type="text" 
                                        name="educations[{{ $index }}][school_name]" 
                                        class="form-control uppercase" 
                                        value="{{ old('educations.'.$index.'.school_name', $education->school_name) }}"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Taken <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                    <input type="text" 
                                        name="educations[{{ $index }}][course_taken]" 
                                        class="form-control uppercase" 
                                        value="{{ old('educations.'.$index.'.course_taken', $education->course_taken) }}"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Year Finished <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="number" 
                                        name="educations[{{ $index }}][year_finished]" 
                                        class="form-control" 
                                        value="{{ old('educations.'.$index.'.year_finished', $education->year_finished) }}"
                                        required
                                        min="1900"
                                        max="{{ date('Y') + 5 }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                    <select name="educations[{{ $index }}][status]" class="form-select" required>
                                        <option value="">Select Status</option>
                                        @foreach($statusOptions as $value => $label)
                                            <option value="{{ $value }}" 
                                                {{ old('educations.'.$index.'.status', $education->status) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($educations->count() < 1)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                <div>You must provide at least 1 education record.</div>
            </div>
        @endif

        <div class="d-flex justify-content-between mt-1">
            <button type="button" id="add-education-btn" class="btn btn-outline-primary rounded-pill mb-3">
                <i class="fas fa-plus me-1"></i> Add Education Record
            </button>

            <button type="submit" class="btn btn-success rounded-pill mb-3">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- Education Card Template -->
<template id="education-template">
    <div class="education-card col-md-6">
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 text-white">
                    Education Record #<span class="education-number"></span>
                </h6>
                <button type="button" class="btn btn-sm btn-light delete-education-btn text-danger rounded-circle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-school"></i></span>
                        <input type="text" name="educations[__INDEX__][school_name]" class="form-control uppercase" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Course Taken <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                        <input type="text" name="educations[__INDEX__][course_taken]" class="form-control uppercase" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Year Finished <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="number" 
                            name="educations[__INDEX__][year_finished]" 
                            class="form-control" 
                            required
                            min="1900"
                            max="{{ date('Y') + 5 }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                        <select name="educations[__INDEX__][status]" class="form-select" required>
                            <option value="">Select Status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Education Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this education record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" id="deleteEducationForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/validation.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let educationIndex = {{ $educations->count() }};
        const addEducationBtn = document.getElementById('add-education-btn');
        const educationsContainer = document.getElementById('educations-container');
        const educationTemplate = document.getElementById('education-template');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteForm = document.getElementById('deleteEducationForm');

        // Add new education record
        addEducationBtn.addEventListener('click', () => {
            const newEducation = educationTemplate.content.cloneNode(true);
            newEducation.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace('__INDEX__', educationIndex);
            });
            newEducation.querySelector('.education-number').textContent = educationIndex + 1;
            educationsContainer.appendChild(newEducation);
            educationIndex++;
        });

        // Handle delete buttons
        document.addEventListener('click', e => {
            const deleteBtn = e.target.closest('.delete-education-btn');
            if (deleteBtn) {
                const educationCard = deleteBtn.closest('.education-card');
                const educationId = deleteBtn.dataset.educationId;

                if (educationId) {
                    // Existing record - set up the delete form
                    deleteForm.action = `/profile/educations/${educationId}`;
                    const modal = new bootstrap.Modal(deleteModal);
                    modal.index();
                } else {
                    // New record - just remove the card
                    educationCard.remove();
                }
            }
        });

        // Back button validation
        const backButton = document.getElementById('back-button');
        backButton.addEventListener('click', function(e) {
            const educationCards = document.querySelectorAll('.education-card');
            if (educationCards.length < 1) {
                e.preventDefault();
                alert('You must provide at least 1 education record before going back.');
                return;
            }

            let isValid = true;
            for (let i = 0; i < 1; i++) {
                const card = educationCards[i];
                const schoolName = card.querySelector('input[name*="[school_name]"]');
                const courseTaken = card.querySelector('input[name*="[course_taken]"]');
                const yearFinished = card.querySelector('input[name*="[year_finished]"]');
                const status = card.querySelector('select[name*="[status]"]');

                if (!schoolName?.value.trim() || !courseTaken?.value.trim() || !yearFinished?.value.trim() || !status?.value) {
                    isValid = false;
                    break;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill out all required fields for at least one education record before going back.');
            }
        });
    });
    </script>
@endpush