@extends('layouts.app')

@section('content')
    <x-success-alert :message="session('success')" />
    <x-error-alert :message="session('error')" />
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="fas fa-users me-1"></i> Employee List</div>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">Add New Employee</a>
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
                                    <td>{{ optional($employee->employeeType)->name }}</td>
                                    <td>
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning"
                                            data-bs-toggle="tooltip" title="Edit Employee">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
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

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const employeeId = button.getAttribute('data-employee');

            const form = document.getElementById('deleteForm');
            form.action = "{{ route('employees.destroy', ':id') }}".replace(':id', employeeId);
        });
    });
</script>