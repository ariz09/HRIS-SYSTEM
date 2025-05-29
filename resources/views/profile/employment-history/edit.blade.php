@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="fas fa-briefcase me-2"></i>
            My Employment History
        </h4>
        <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary rounded-pill"
            id="back-button">
            <i class="fas fa-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <form action="{{ route('profile.employment-history.update') }}" method="POST"
        id="employmentHistoriesForm">
        @csrf
        @method('PUT')

        <div id="histories-container" class="row g-4">
            @foreach($histories as $index => $history)
                <div class="history-card col-6">
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 text-white">
                                Employment Record #{{ $index + 1 }}
                            </h6>
                            @if($history->id)
                                <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-history-id="{{ $history->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($history->id)
                                <input type="hidden" name="histories[{{ $index }}][id]" value="{{ $history->id }}">
                            @endif
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <input type="text" 
                                            name="histories[{{ $index }}][job_title]" 
                                            class="form-control uppercase" 
                                            value="{{ old('histories.'.$index.'.job_title', $history->job_title) }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" 
                                            name="histories[{{ $index }}][company_name]" 
                                            class="form-control uppercase" 
                                            value="{{ old('histories.'.$index.'.company_name', $history->company_name) }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Company Address <span class="text-danger">*</span></label>
                                <textarea name="histories[{{ $index }}][company_address]" 
                                    class="form-control uppercase" 
                                    rows="3"
                                    required>{{ old('histories.'.$index.'.company_address', $history->company_address) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" 
                                            name="histories[{{ $index }}][start_date]" 
                                            class="form-control" 
                                            value="{{ old('histories.'.$index.'.start_date', $history->start_date?->format('Y-m-d')) }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">End Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" 
                                            name="histories[{{ $index }}][end_date]" 
                                            class="form-control" 
                                            value="{{ old('histories.'.$index.'.end_date', $history->end_date?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="form-text">Leave blank if currently employed here</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between mt-1">
            <button type="button" id="add-history-btn" class="btn btn-outline-primary rounded-pill mb-3">
                <i class="fas fa-plus me-1"></i> Add Employment History
            </button>

            <button type="submit" class="btn btn-success rounded-pill mb-3">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>

<!-- History Card Template -->
<template id="history-template">
    <div class="history-card col-6">
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 text-white">
                    Employment Record #<span class="history-number"></span>
                </h6>
                <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="text" name="histories[__INDEX__][job_title]" class="form-control uppercase" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <input type="text" name="histories[__INDEX__][company_name]" class="form-control uppercase" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Company Address <span class="text-danger">*</span></label>
                    <textarea name="histories[__INDEX__][company_address]" class="form-control uppercase" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="histories[__INDEX__][start_date]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">End Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="histories[__INDEX__][end_date]" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteHistoryForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Delete Employment History</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this employment history record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>            
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
            let historyIndex = {{ $histories->count() }};
            const addHistoryBtn = document.getElementById('add-history-btn');
            const historiesContainer = document.getElementById('histories-container');
            const historyTemplate = document.getElementById('history-template');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            const deleteForm = document.getElementById('deleteHistoryForm');
            
            // Add new history
            addHistoryBtn.addEventListener('click', () => {
                const newHistory = historyTemplate.content.cloneNode(true);
                newHistory.querySelectorAll('[name]').forEach(input => {
                    input.name = input.name.replace('__INDEX__', historyIndex);
                });
                newHistory.querySelector('.history-number').textContent = historyIndex + 1;
                historiesContainer.appendChild(newHistory);
                historyIndex++;
                
                // Scroll to new history
                historiesContainer.lastElementChild.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest' 
                });
            });

            // Handle delete buttons - event delegation
            document.addEventListener('click', e => {
                const deleteBtn = e.target.closest('.delete-history-btn');
                if (!deleteBtn) return;
                
                const historyCard = deleteBtn.closest('.history-card');
                const historyId = deleteBtn.dataset.historyId;

                if (historyId) {
                    // Existing record - show confirmation modal
                    deleteForm.action = `/profile/employment-history/${historyId}`;
                    deleteModal.show();
                } else {
                    // New record - just remove the card
                    historyCard.remove();
                    updateHistoryNumbers();
                    historyIndex--;
                }
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

            // Update history numbers when one is deleted
            function updateHistoryNumbers() {
                document.querySelectorAll('.history-card').forEach((card, index) => {
                    const header = card.querySelector('.card-header h6');
                    if (header) header.textContent = `Employment Record #${index + 1}`;
                });
            }

            // Date validation
            document.addEventListener('change', e => {
                if (e.target.name?.includes('[end_date]') && e.target.value) {
                    const startDateInput = e.target.closest('.card-body').querySelector('input[name$="[start_date]"]');
                    if (startDateInput?.value && new Date(e.target.value) < new Date(startDateInput.value)) {
                        showAlert('warning', 'End date must be after start date');
                        e.target.value = '';
                    }
                }
            });

            // Back button validation
            const backButton = document.getElementById('back-button');
            if (backButton) {
                backButton.addEventListener('click', function(e) {
                    if (!validateHistories()) {
                        e.preventDefault();
                    }
                });
            }

            // Validate histories before leaving
            function validateHistories() {
                const historyCards = document.querySelectorAll('.history-card');
                
                let isValid = true;
                const invalidFields = [];
                
                for (let i = 0; i < historyCards.length; i++) {
                    const card = historyCards[i];
                    const inputs = {
                        jobTitle: card.querySelector('input[name*="[job_title]"]'),
                        companyName: card.querySelector('input[name*="[company_name]"]'),
                        companyAddress: card.querySelector('textarea[name*="[company_address]"]'),
                        startDate: card.querySelector('input[name*="[start_date]"]')
                    };

                    if (!inputs.jobTitle?.value.trim()) invalidFields.push('Job Title');
                    if (!inputs.companyName?.value.trim()) invalidFields.push('Company Name');
                    if (!inputs.companyAddress?.value.trim()) invalidFields.push('Company Address');
                    if (!inputs.startDate?.value.trim()) invalidFields.push('Start Date');
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