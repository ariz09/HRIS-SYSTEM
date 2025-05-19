@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Employee List</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-users me-2"></i> <strong>Employee List</strong>
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
                                <td>{{ optional($employee->employmentType)->name ?? 'Not specified' }}</td>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUploadModalLabel">Upload CSV File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('employees.template.download') }}"
                            class="btn btn-primary btn-sm" target="_blank"  download>
                            Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@push('scripts')
<!-- Ensure jQuery is loaded first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS and JS (Bootstrap 5 + Responsive + Buttons) -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#employeesTable').DataTable({
            responsive: true,
            dom: '<"top d-flex justify-content-between align-items-center"lfB>rt<"bottom d-flex justify-content-between align-items-center"ip><"clear">',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv me-1"></i> Export CSV',
                    className: 'btn btn-success btn-sm',
                    title: 'Employment_Types',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy me-1"></i> Copy',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pageLength: 10,
            processing: true,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Loading...'
            },
            initComplete: function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dt-buttons').addClass('btn-group');
                $('.dt-buttons button').removeClass('btn-secondary');
            }
        });
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


