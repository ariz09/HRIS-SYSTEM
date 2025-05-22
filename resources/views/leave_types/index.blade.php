@extends('layouts.app')
@section('content')
<x-success-alert :message="session('success')" />
<div class="container-fluid px-4">
    <h1 class="mt-4">Leave Type Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Leave Types</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex bg-danger justify-content-between align-items-center">
            <div class="text-white">
                <i class="fas fa-table me-1"></i>
                Leave Type List
            </div>
            <button type="button" class="btn btn-light  text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Add Leave Type
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="leaveTypesTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $leaveType)
                        <tr>
                            <td>{{ $leaveType->name }}</td>
                            <td>{{ $leaveType->description }}</td>
                            <td>
                                @if($leaveType->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $leaveType->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $leaveType->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('leave_types.store') }}" method="POST">
            @csrf
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Create Leave Type</h5>
                <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Edit Leave Type</h5>
                <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" id="edit-name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="edit-description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="edit-status" name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success btn-sm" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this leave type?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" type="button">Cancel</button>
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')

<script>

$(document).ready(function() {
    setDatatable("leaveTypesTable"); 

    $('#leaveTypesTable').on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get('/leave-types/' + id + '/edit', function (data) {
            $('#edit-name').val(data.name);
            $('#edit-description').val(data.description);  // Add this line for description
            $('#edit-status').val(data.status ? '1' : '0');
            $('#editForm').attr('action', '/leave-types/' + id);
            $('#editModal').modal('show');
        });
    });

    $('#leaveTypesTable').on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        $('#deleteForm').attr('action', '/leave-types/' + id);
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush
