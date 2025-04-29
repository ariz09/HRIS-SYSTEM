@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<div class="container-fluid px-4">
    <h1 class="mt-4">CDM Level Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">CDM Levels</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><i class="fas fa-layer-group me-1"></i> CDM Level List</div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Add CDM Level
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="cdmLevelsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cdmLevels as $cdmLevel)
                        <tr>
                            <td>{{ $cdmLevel->name }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $cdmLevel->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $cdmLevel->id }}">
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
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('cdmlevels.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New CDM Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="create-name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="create-name" required>
                </div>
                <div class="mb-3">
                    <label for="create-description" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="create-description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editForm" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit CDM Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit-name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="edit-name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST" class="modal-content">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this CDM Level? This cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#cdmLevelsTable').DataTable();

        $('.edit-btn').click(function () {
            var id = $(this).data('id');
            $.get('/cdmlevels/' + id + '/edit', function (data) {
                $('#edit-name').val(data.name);
                $('#editForm').attr('action', '/cdmlevels/' + id);
                $('#editModal').modal('show');
            }).fail(function () {
                alert('Failed to fetch data. Please try again.');
            });
        });

        $('.delete-btn').click(function () {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '/cdmlevels/' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endpush
