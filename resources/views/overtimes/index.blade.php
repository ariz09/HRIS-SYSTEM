@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Overtime Management</h3>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Overtime</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- New Overtime Request Form -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">New Overtime Request</div>
        <div class="card-body">
            <form action="{{ route('overtimes.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="employee_number" class="form-label">Employee</label>
                        <select name="employee_number" id="employee_number" class="form-select @error('employee_number') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Select Employee --</option>
                            @foreach($employees as $employee)
                                @if($employee->personalInfo)
                                    <option value="{{ $employee->employee_number }}">
                                        {{ strtoupper($employee->personalInfo->last_name) }},
                                        {{ strtoupper($employee->personalInfo->first_name) }}
                                        {{ $employee->personalInfo->middle_name ? strtoupper($employee->personalInfo->middle_name) : '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('employee_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start" class="form-label">Start Date & Time</label>
                        <input type="datetime-local" name="start" id="start"
                               class="form-control @error('start') is-invalid @enderror" required>
                        @error('start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="end" class="form-label">End Date & Time</label>
                        <input type="datetime-local" name="end" id="end"
                               class="form-control @error('end') is-invalid @enderror" required>
                        @error('end') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea name="reason" id="reason" rows="3"
                              class="form-control @error('reason') is-invalid @enderror" required></textarea>
                    @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Overtime Table -->
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>Overtime Requests</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="overtimeTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Total Hours</th>                                    
                            <th>Department</th>
                            <th>Position</th>   
                            <th>Employee Type</th>   
                            <th>Reason</th>
                            <th>Remarks</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                        @php
                            use Carbon\Carbon;
                        @endphp
                    <tbody>
                        @forelse($overtimes as $index => $overtime)
                            @php
                                $start = Carbon::parse($overtime->start);
                                $end = Carbon::parse($overtime->end);
                                $totalHours = $start->diffInMinutes($end) / 60;
                            @endphp
                            <tr>
                                                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($overtime->employee && $overtime->employee->personalInfo)
                                    {{ strtoupper($overtime->employee->personalInfo->last_name) }},
                                    {{ strtoupper($overtime->employee->personalInfo->first_name) }}
                                    {{ $overtime->employee->personalInfo->middle_name ? strtoupper($overtime->employee->personalInfo->middle_name) : '' }}
                                @else
                                    {{ $overtime->employee_number }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'declined' => 'danger'
                                    ][$overtime->status ?? 'pending'];
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($overtime->status ?? ""  ) }}</span>
                            </td>
                            <td>{{ $overtime->start->format('Y-m-d H:i') ?? "" }}</td>
                            <td>{{ $overtime->end->format('Y-m-d H:i') ?? "" }}</td>
                            <td>{{ number_format($totalHours ?? "" , 2) }}</td>
                            <td>{{ $overtime->employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $overtime->employee->position->name ?? 'N/A' }}</td>
                            <td>{{ $overtime->employee->employmentType->name ?? 'N/A' }}</td>
                            <td>{{ Str::limit($overtime->reason ?? '', 50) }}</td>
                            <td>{{ Str::limit($overtime->remarks ?? '', 50) }}</td>
                            <td class="text-start">
                                  <button class="btn btn-sm btn-primary text-white me-1" data-bs-toggle="modal" data-bs-target="#actionModal{{ $overtime->id }}">
                                    Approve/Decline
                                 </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $overtime->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                     <!-- Take Action Modal -->
                                    <div class="modal fade" id="actionModal{{ $overtime->id }}" tabindex="-1" aria-labelledby="actionModalLabel{{ $overtime->id }}" aria-hidden="true" >
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                           <form method="POST" action="{{ route('overtimes.update', $overtime->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content ">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="actionModalLabel{{ $overtime->id }}">Take Action on Overtime</h5>
                                                        
                                                        <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                                                            <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="status{{ $overtime->id }}" class="form-label">Status</label>
                                                            <select name="status" id="status{{ $overtime->id }}" class="form-select" required>
                                                                <option value="" disabled selected>Choose status</option>
                                                                <option value="approved">Approve</option>
                                                                <option value="declined">Decline</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="remarks{{ $overtime->id }}" class="form-label">Remarks (optional)</label>
                                                            <textarea name="remarks" id="remarks{{ $overtime->id }}" rows="3" class="form-control" placeholder="Add remarks here...">{{ old('remarks') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $overtime->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $overtime->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $overtime->id }}">Confirm Delete</h5>
                                                   <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                                                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this overtime request?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('overtimes.destroy', $overtime->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">No overtime records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')

<style>
    #overtimeTable td {
        vertical-align: middle;
    }
    #overtimeTable th, #overtimeTable td {
        white-space: nowrap;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endpush


@push('scripts')

<script>
$(document).ready(function() {
    var table = $('#overtimeTable').DataTable({
        responsive: true
        dom: '<"top d-flex justify-content-between align-items-center mb-2"Bf>rt<"bottom mt-3"ip>',
        buttons: [
            {
                extend: 'csv',
                text: 'CSV',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'excel',
                text: 'Excel',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdf',
                text: 'PDF',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: { columns: ':not(:last-child)' }
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        columnDefs: [
            { responsivePriority: 1, targets: 1 }, // Employee name
            { responsivePriority: 2, targets: 2 }, // Status
            { responsivePriority: 3, targets: -1 }, // Actions (last column)
            { className: "text-center", targets: [0, 2, 4, 5] }, // Center align these columns
            { className: "text-start", targets: [1, 3, 6, 7, 8, 9, 10] } // Left align these columns
        ],
    });

    setTimeout(() => {
        $('#success-alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
