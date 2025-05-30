@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Agency Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Agencies</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Agency List
                </div>
                <div>
                    <button type="button" class="btn btn-light btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Add Agency
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="agenciesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th>Agency Name</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agencies as $agency)
                        <tr>
                            <td class="d-none">{{ $loop->iteration }}</td>
                            <td>{{ $agency->name }}</td>
                            <td>
                                @if($agency->logo)
                                    <img src="{{ asset('storage/' . $agency->logo) }}" alt="Agency Logo" width="40" height="40">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($agency->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" 
                                        data-id="{{ $agency->id }}"
                                        data-name="{{ $agency->name }}"
                                        data-status="{{ $agency->status }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $agency->id }}">
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
        <div class="modal-content">
            <div class="modal-header  bg-danger text-white">
                <h5 class="modal-title" id="createModalLabel">Create New Agency</h5>
                    <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
            </div>
            <form action="{{ route('agencies.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name" class="form-label">Agency Name</label>
                        <input type="text" class="form-control" id="create-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-status" class="form-label">Status</label>
                        <select class="form-select" id="create-status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-logo" class="form-label">Upload Logo (optional)</label>
                        <input type="file" class="form-control" id="create-logo" name="logo" accept="image/*">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save Agency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="editModalLabel">Edit Agency</h5>
                    <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Agency Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-status" class="form-label">Status</label>
                        <select class="form-select" id="edit-status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-logo" class="form-label">Change Logo (optional)</label>
                        <input type="file" class="form-control" id="edit-logo" name="logo" accept="image/*">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Update Agency</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this agency? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')


<script>

$(document).ready(function () {
     
        setDatatable("agenciesTable", {
            dom: 'rtip', // minimal layout
            buttons: [] // no export buttons
        }); 

    });

// Edit button click handler
$('.edit-btn').click(function() {
    var agencyId = $(this).data('id');
    $('#editId').val(agencyId);
    $('#edit-name').val($(this).data('name'));
    $('#edit-status').val($(this).data('status') ? '1' : '0');
    $('#editForm').attr('action', '/agencies/' + agencyId);
    $('#editModal').modal('show');
});

// Delete button click handler
$('.delete-btn').click(function() {
    var agencyId = $(this).data('id');
    $('#deleteForm').attr('action', '/agencies/' + agencyId);
    $('#deleteModal').modal('show');
});

</script>
@endpush
