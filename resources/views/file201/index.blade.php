@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="container">
    <h3 class="mb-4">201 File Management</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">201 Files</li>
    </ol>

    {{-- Upload Form --}}
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">Upload 201 File</div>
        <div class="card-body">
            <form action="{{ route('file201.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
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

                    <div class="col-md-6">
                        <label for="file_type" class="form-label">File Type</label>
                        <select name="file_type" id="file_type" class="form-select @error('file_type') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Select File Type --</option>
                            @foreach(\App\Models\File201::fileTypes() as $type)
                                <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                        @error('file_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Attachment (Optional)</label>
                    <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success">Upload</button>
            </form>
        </div>
    </div>

    {{-- Uploaded Files Table --}}
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>Uploaded 201 Files</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" id="file201Table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>File Type</th>
                            <th>Attachment</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($files as $index => $file)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $emp = $employees->firstWhere('employee_number', $file->employee_number);
                                    @endphp
                                    @if($emp && $emp->personalInfo)
                                        {{ strtoupper($emp->personalInfo->last_name) }}, 
                                        {{ strtoupper($emp->personalInfo->first_name) }} 
                                        {{ $emp->personalInfo->middle_name ? strtoupper($emp->personalInfo->middle_name) : '' }}
                                    @else
                                        {{ $file->employee_number }}
                                    @endif
                                </td>
                                <td>{{ ucwords(str_replace('_', ' ', $file->file_type)) }}</td>
                                <td>
                                    @if($file->attachment)
                                        <a href="{{ Storage::url($file->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>{{ $file->user->name ?? 'Unknown' }}</td>
                                <td>{{ $file->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $file->id }}">
                                        Delete
                                    </button>

                                    {{-- Delete Confirmation Modal --}}
                                    <div class="modal fade" id="deleteModal{{ $file->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $file->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $file->id }}">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this 201 file?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('file201.destroy', $file->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Yes, Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No 201 files uploaded yet.</td>
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
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">

@endpush

@push('scripts')
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#file201Table').DataTable({
        responsive: true,
        dom: '<"top"lf>rt<"bottom"ip>',
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
            { responsivePriority: 2, targets: 2 }, // File type
            { responsivePriority: 3, targets: -1 } // Action column
        ]
    });

    // Auto-hide success alert after 5 seconds
    setTimeout(function() {
        $('#success-alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush