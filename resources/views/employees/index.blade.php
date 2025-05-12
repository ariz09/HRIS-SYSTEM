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
            <div class="card-header d-flex justify-content-between align-items-center p-4 rounded-xl shadow-sm">
                <div>
                    <i class="fas fa-users me-2"></i> <strong>Employee List</strong>
                </div>
                <div>
                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Add New Employee
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                    Bulk Upload Employees
                </button>
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
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-employee="{{ $employee->id }}"
                                            title="Delete Employee">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }}
                        employees
                    </div>
                    <div>
                        {{ $employees->links() }}
                    </div>
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
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition"
                        target="_blank"
                        download>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v8m0 0l-3-3m3 3l3-3M12 4v8"/>
                            </svg>
                            Download Template
                        </a>
                    </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </form>
    </div>
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTable
            $('#employeesTable').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                searching: true,
                ordering: true,
                pageLength: 10,  // Adjust the number of records per page
            });

            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const employeeId = button.getAttribute('data-employee');

                const form = document.getElementById('deleteForm');
                form.action = "{{ route('employees.destroy', ':id') }}".replace(':id', employeeId);
            });
        });
    </script>
@endsection
