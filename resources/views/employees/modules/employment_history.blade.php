@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee->id) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Employment History</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-history me-1"></i> Employment History
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.history', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div id="history-container">
                    @forelse(old('employment_history', $employee->employmentHistories->toArray()) as $index => $history)
                        <div class="history-item card mb-3">
                            <div class="card-body">
                                @if($loop->index > 0)
                                    <button type="button" class="btn-close float-end remove-history" aria-label="Close"></button>
                                @endif
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Company Name</label>
                                        <input type="text" name="employment_history[{{ $index }}][company_name]" class="form-control" 
                                               value="{{ $history['company_name'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Position</label>
                                        <input type="text" name="employment_history[{{ $index }}][position]" class="form-control" 
                                               value="{{ $history['position'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Start Date</label>
                                        <input type="date" name="employment_history[{{ $index }}][start_date]" class="form-control" 
                                               value="{{ $history['start_date'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="employment_history[{{ $index }}][end_date]" class="form-control" 
                                               value="{{ $history['end_date'] ?? '' }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Salary</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" name="employment_history[{{ $index }}][salary]" class="form-control" step="0.01" 
                                                   value="{{ $history['salary'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Responsibilities</label>
                                        <textarea name="employment_history[{{ $index }}][responsibilities]" class="form-control">{{ $history['responsibilities'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Reason for Leaving</label>
                                        <input type="text" name="employment_history[{{ $index }}][reason_for_leaving]" class="form-control" 
                                               value="{{ $history['reason_for_leaving'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="history-item card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Company Name</label>
                                        <input type="text" name="employment_history[0][company_name]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Position</label>
                                        <input type="text" name="employment_history[0][position]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Start Date</label>
                                        <input type="date" name="employment_history[0][start_date]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="employment_history[0][end_date]" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Salary</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" name="employment_history[0][salary]" class="form-control" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Responsibilities</label>
                                        <textarea name="employment_history[0][responsibilities]" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Reason for Leaving</label>
                                        <input type="text" name="employment_history[0][reason_for_leaving]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mb-3">
                    <button type="button" id="add-history" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Another Employment History
                    </button>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Employment History
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let historyIndex = {{ count(old('employment_history', $employee->employmentHistories->toArray())) }};
    
    // Add new employment history item
    document.getElementById('add-history').addEventListener('click', function() {
        const container = document.getElementById('history-container');
        const newItem = document.createElement('div');
        newItem.className = 'history-item card mb-3';
        newItem.innerHTML = `
            <div class="card-body">
                <button type="button" class="btn-close float-end remove-history" aria-label="Close"></button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Company Name</label>
                        <input type="text" name="employment_history[${historyIndex}][company_name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">Position</label>
                        <input type="text" name="employment_history[${historyIndex}][position]" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Start Date</label>
                        <input type="date" name="employment_history[${historyIndex}][start_date]" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="employment_history[${historyIndex}][end_date]" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Salary</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="employment_history[${historyIndex}][salary]" class="form-control" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Responsibilities</label>
                        <textarea name="employment_history[${historyIndex}][responsibilities]" class="form-control"></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Reason for Leaving</label>
                        <input type="text" name="employment_history[${historyIndex}][reason_for_leaving]" class="form-control">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        historyIndex++;
    });

    // Remove employment history item
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-history')) {
            e.target.closest('.history-item').remove();
        }
    });
});
</script>
@endpush
@endsection