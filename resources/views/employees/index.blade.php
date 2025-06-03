@extends('layouts.app')
@if(session('error_details'))
<div class="alert alert-danger">
    <h5>Detailed Errors:</h5>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Row #</th>
                    <th>Errors</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('error_details') as $error)
                    <tr>
                        <td>{{ $error['row'] }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($error['errors'] as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <pre class="mb-0">{{ json_encode($error['data'] ?? 'No data', JSON_PRETTY_PRINT) }}</pre>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Employee List</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header  bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2"></i> 
                    Employee List
                </div>
                <div>
                    <a href="{{ route('employees.create') }}" class="btn btn-light btn-sm text-danger">
                        <i class="fas fa-plus-circle"></i> Add New Employee
                    </a>
                    <button type="button" class="btn btn-light btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                        <i class="fas fa-upload"></i> Bulk Upload
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="employeesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Employee Number</th>
                            <th>Full Name</th>
                            <th>Agency</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Employee Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_number }}</td>
                                <td>{{ ucfirst(strtolower(optional($employee->personalInfo)->first_name)) }}
                                    {{ ucfirst(strtolower(optional($employee->personalInfo)->last_name)) }}
                                </td>
                                <td>{{ optional($employee->agency)->name }}</td>
                                <td>{{ optional($employee->position)->name }}</td>
                                <td>{{ optional($employee->employmentStatus)->name }}</td>
                                <td>{{ optional($employee->employmentType)->name ?? '' }}</td>
                                <td>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm"
                                        data-bs-toggle="tooltip" title="Edit Employee">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                 {{--    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-employee="{{ $employee->id }}"
                                        title="Delete Employee">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this employee? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employees.bulkUpload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow-sm">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="bulkUploadModalLabel">Upload CSV File</h5>
                    <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal"
                        style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" name="employee_csv" id="employee_csv" class="form-control" required accept=".csv">
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('employees.download.template') }}"
                            class="btn btn-secondary text-white btn-sm" target="_blank">
                            Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@push('scripts')
<!-- Ensure jQuery is loaded first -->

<script>
    $(document).ready(function () {
        setDatatable("employeesTable"); //simplified initialization for data table

     /*    setDatatable("simpleTable", {
            dom: 'rtip', // minimal layout
            buttons: [] // no export buttons
        }); */

    });



        /* // Delete modal handler remains the same
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const employeeId = button.getAttribute('data-employee');
            const form = document.getElementById('deleteForm');
            form.action = "{{ route('employees.destroy', ':id') }}".replace(':id', employeeId);
        }); */
</script>
@endpush


