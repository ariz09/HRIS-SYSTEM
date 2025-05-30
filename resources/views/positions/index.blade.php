@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<div class="container-fluid px-4">
    <h1 class="mt-4">Position Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Positions</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header  bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Position List
                </div>
                <div>
                    <button type="button" class="btn btn-light text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Add Position
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="positionsTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th class="d-none">Position Code</th>
                            <th>Position Name</th>
                            <th>CDM Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($positions as $position)
                        <tr>
                            <td class="d-none">{{ $loop->iteration }}</td>
                            <td class="d-none">{{ $position->code }}</td>
                            <td>{{ $position->name }}</td>
                            <td>{{ $position->cdmLevel?->name ?? '—' }}</td>
                            <td>
                                @if($position->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $position->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $position->id }}">
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
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="createModalLabel">Create New Position</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
            </div>
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-name" class="form-label">Position Name</label>
                        <input type="text" class="form-control" id="create-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-cdm-level" class="form-label">CDM Level</label>
                        <select class="form-select" id="create-cdm-level" name="cdm_level_id" required>
                            <option value="">Select CDM Level</option>
                            @foreach($cdmLevels as $cdm) <!-- Changed this part to use the passed $cdmLevels -->
                                <option value="{{ $cdm->id }}">{{ $cdm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-status" class="form-label">Status</label>
                        <select class="form-select" id="create-status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save Position</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Position</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Position Name</label>
                        <input type="text" class="form-control" id="edit-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-cdm-level" class="form-label">CDM Level</label>
                        <select class="form-select" id="edit-cdm-level" name="cdm_level_id" required>
                            <option value="">Select CDM Level</option>
                            @foreach($cdmLevels as $cdm)
                                <option value="{{ $cdm->id }}">{{ $cdm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-status" class="form-label">Status</label>
                        <select class="form-select" id="edit-status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Update Position</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Delete Position</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this position?</p>
                    <p class="text-danger fw-bold mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#positionsTable').DataTable({
            responsive: true
        });

        // Auto-close alerts after 3 seconds
        setTimeout(function() {
            $('.auto-close').alert('close');
        }, 3000);

        // Edit Position
       $('.edit-btn').click(function() {
            var positionId = $(this).data('id');
            $.get('/positions/' + positionId + '/edit', function(data) {
                $('#edit-code').val(data.code);
                $('#edit-name').val(data.name);
                $('#edit-cdm-level').val(data.cdm_level_id);
                $('#edit-status').val(data.status ? '1' : '0');
                $('#editForm').attr('action', '/positions/' + positionId);
                $('#editModal').modal('show');
            });
        });

        // Delete Position
        $('.delete-btn').click(function() {
            var positionId = $(this).data('id');
            $('#deleteForm').attr('action', '/positions/' + positionId);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endpush
