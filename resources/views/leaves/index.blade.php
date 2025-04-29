@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employees</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">Add Employee</a>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->department->name }}</td>
                <td>{{ $employee->position->title }}</td>
                <td>
                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <!-- Add delete button with form -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
