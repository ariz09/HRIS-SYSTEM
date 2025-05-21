@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/validation.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mt-4">
                <i class="fas fa-briefcase me-2"></i>
                Employment History for {{ $employeeName }}
            </h5>
            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-outline-secondary rounded-pill"
                id="back-button">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <form action="{{ route('employees.employment-histories.update', $employee) }}" method="POST"
            id="employmentHistoriesForm">
            @csrf
            @method('PUT')

            <div id="histories-container" class="row g-4">
                @foreach($histories as $index => $history)
                    @include('employees.partials.employment-history-card', ['index' => $index, 'history' => $history])
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
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2"
                    style="font-size: 0.875rem;">
                    <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                        Employment History #<span class="history-number"></span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                        style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                <input type="text" name="histories[__INDEX__][job_title]" class="form-control uppercase"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                <input type="text" name="histories[__INDEX__][company_name]" class="form-control uppercase" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Company Address</label>
                            <textarea name="histories[__INDEX__][company_address]" class="form-control uppercase" rows="3"></textarea>
                        </div>

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
                        <h6 class="modal-title" id="deleteConfirmModalLabel">Delete Employment History</h6>
                        <button type="button" class="btn btn-sm btn-light delete-education-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                            <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this employment history record?
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
         
         document.addEventListener('input', function(e) {
            if (e.target.classList.contains('uppercase')) {
                e.target.value = e.target.value.toUpperCase();
            }
        });


    let historyIndex = {{ $histories->count() }};
    let currentHistoryCard = null; // Track the card being deleted

    const addHistoryBtn = document.getElementById('add-history-btn');
    const historiesContainer = document.getElementById('histories-container');
    const historyTemplate = document.getElementById('history-template');

    // Add new history
    addHistoryBtn.addEventListener('click', () => {
        const newHistory = historyTemplate.content.cloneNode(true);
        const newIndex = historyIndex;
        
        newHistory.querySelectorAll('[name]').forEach(input => {
            input.name = input.name.replace('__INDEX__', newIndex);
        });

        newHistory.querySelector('.history-number').textContent = newIndex + 1;
        historiesContainer.appendChild(newHistory);
        historyIndex++;
    });

    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteForm = document.getElementById('deleteHistoryForm');

    // Delete history handler
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-history-btn');
        if (deleteBtn) {

            const currentHistoryCard = deleteBtn.closest('.history-card');
            const historyId = currentHistoryCard.querySelector('input[name$="[id]"]')?.value;

            if (historyId) {
                // Existing record - set up proper delete URL
                deleteForm.action = `/employees/{{ $employee->employee_number }}/employment-histories/${historyId}`;
                
                // Show confirmation modal
                const modal = new bootstrap.Modal(deleteModal);
                modal.show();
            } else {
                // New record (not saved yet) - show confirmation before removal
                const modal = new bootstrap.Modal(deleteModal);
                
                // Temporarily modify the form to handle unsaved records
                deleteForm.action = '';
                deleteForm.onsubmit = function(e) {
                    e.preventDefault();
                    currentHistoryCard.remove();
                    modal.hide();
                    return false;
                };
                
                modal.show();
            }
        }
    });

    // Handle form submission from modal for saved records
    deleteForm.addEventListener('submit', function(e) {
        if (!this.action) return; // Let the custom handler work for unsaved records
        
        e.preventDefault();
        
        fetch(this.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(deleteModal);
                modal.hide();
                currentHistoryCard.remove();
            }
        })
        .catch(error => {
            //console.error('Error:', error);
            //alert('Failed to delete record. Please try again.');
             location.reload();
        });
    });

    // Date validation
    document.addEventListener('change', e => {
        if (e.target.name?.includes('[end_date]') && e.target.value) {
            const startDateInput = e.target.closest('.card-body').querySelector('input[name$="[start_date]"]');
            if (startDateInput?.value && new Date(e.target.value) < new Date(startDateInput.value)) {
                alert('End date must be after start date');
                e.target.value = '';
            }
        }
    });
});
    </script>
@endpush