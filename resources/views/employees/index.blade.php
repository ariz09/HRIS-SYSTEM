@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Employees</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Employee List</div>
            <div>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Employee
                </a>
                <a href="{{ route('admin.employees.bulk-upload') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-import"></i> Bulk Upload
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="employeesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Employee #</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_number ?? '-' }}</td>
                            <td>{{ ($employee->last_name ?? '') . ', ' . ($employee->first_name ?? '') }}</td>
                            <td>{{ $employee->position ? $employee->position->name : '-' }}</td>
                            <td>{{ $employee->department ? $employee->department->name : '-' }}</td>
                            <td>
                                @if($employee->employment_status_id === 1)
                                    <span class="badge bg-success">Active</span>
                                @elseif($employee->employment_status_id === 0)
                                    <span class="badge bg-secondary">Inactive</span>
                                @else
                                    <span class="badge bg-warning">No Status</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-info btn-sm" title="View" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary btn-sm" title="Edit" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" data-bs-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this employee?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#employeesTable').DataTable({
            responsive: true
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush