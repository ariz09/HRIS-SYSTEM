@extends('layouts.app')

@section('content')
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
                                    {{ ucfirst(strtolower(optional($employee->personalInfo)->last_name)) }}</td>
                                <td>{{ optional($employee->agency)->name }}</td>
                                <td>{{ optional($employee->position)->name }}</td>
                                <td>{{ optional($employee->employmentStatus)->name }}</td>
                                <td>{{ optional($employee->employeeType)->name }}</td>
                                <td>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
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
