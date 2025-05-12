@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Personal Information</h1>

    <!-- Create Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        Add Personal Info
    </button>

    <!-- Personal Info Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Civil Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personalInfos as $info)
            <tr>
                <td>{{ $info->first_name }} {{ $info->last_name }}</td>
                <td>{{ $info->email }}</td>
                <td>{{ $info->gender }}</td>
                <td>{{ $info->phone_number }}</td>
                <td>{{ $info->civil_status }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-{{ $info->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $info->id }}">Delete</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal-{{ $info->id }}" tabindex="-1" aria-labelledby="editLabel{{ $info->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('employees.personal_infos.update', $info->id) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editLabel{{ $info->id }}">Edit Personal Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" value="{{ $info->first_name }}" required>
                            <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" value="{{ $info->last_name }}" required>
                            <input type="email" name="email" class="form-control mb-2" placeholder="Email" value="{{ $info->email }}">
                            <select name="gender" class="form-control mb-2" required>
                                <option value="Male" {{ $info->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $info->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ $info->gender == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" name="phone_number" class="form-control mb-2" placeholder="Phone Number" value="{{ $info->phone_number }}">
                            <input type="text" name="civil_status" class="form-control mb-2" placeholder="Civil Status" value="{{ $info->civil_status }}">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal-{{ $info->id }}" tabindex="-1" aria-labelledby="deleteLabel{{ $info->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('employees.personal_infos.destroy', $info->id) }}" method="POST" class="modal-content">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteLabel{{ $info->id }}">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete {{ $info->first_name }} {{ $info->last_name }}?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employees.personal_infos.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createLabel">Add Personal Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="number" name="user_id" class="form-control mb-2" placeholder="User ID" required>
                <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
                <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email">
                <select name="gender" class="form-control mb-2" required>
                    <option value="" disabled selected>Choose Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" name="phone_number" class="form-control mb-2" placeholder="Phone Number">
                <input type="text" name="civil_status" class="form-control mb-2" placeholder="Civil Status">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
