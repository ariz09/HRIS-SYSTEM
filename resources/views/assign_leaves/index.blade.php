@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Leave Assignment Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Leave Assignments</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Leave Assignments List
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Assign Leave
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="assignLeavesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th>CDM Level</th>
                            <th>Leave Count</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignLeaves as $assign)
                        <tr>
                            <td class="d-none">{{ $loop->iteration }}</td>
                            <td>{{ $assign->cdmLevel->name }}</td> <!-- Changed from department to cdmLevel -->
                            <td>{{ $assign->leave_count }}</td>
                            <td>{{ $assign->notes ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" 
                                        data-id="{{ $assign->id }}"
                                        data-cdm-level="{{ $assign->cdm_level_id }}"
                                        data-leave="{{ $assign->leave_count }}"
                                        data-notes="{{ $assign->notes }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $assign->id }}">
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
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Assign New Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('assign_leaves.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-cdm-level" class="form-label">CDM Level</label>
                        <select class="form-select" id="create-cdm-level" name="cdm_level_id" required>
                            @foreach($cdmLevels as $cdmLevel) <!-- Changed to cdmLevels -->
                            <option value="{{ $cdmLevel->id }}">{{ $cdmLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="create-leave-count" class="form-label">Leave Count</label>
                        <input type="number" class="form-control" id="create-leave-count" name="leave_count" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="create-notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="create-notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Leave Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="edit-cdm-level" class="form-label">CDM Level</label>
                        <select class="form-select" id="edit-cdm-level" name="cdm_level_id" required>
                            @foreach($cdmLevels as $cdmLevel) <!-- Changed to cdmLevels -->
                            <option value="{{ $cdmLevel->id }}">{{ $cdmLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-leave-count" class="form-label">Leave Count</label>
                        <input type="number" class="form-control" id="edit-leave-count" name="leave_count" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit-notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this leave assignment? This action cannot be undone.
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

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap5.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // Auto-dismiss success alert after 3 seconds
    setTimeout(function () {
        let alert = document.getElementById('success-alert');
        if (alert) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);

    $(document).ready(function() {
        // Initialize DataTable with responsive plugin
        $('#assignLeavesTable').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details for ' + data[1]; // CDM Level Name
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            }
        });

        // Edit button click handler
        $('.edit-btn').click(function() {
            var assignId = $(this).data('id');
            $('#editId').val(assignId);
            $('#edit-cdm-level').val($(this).data('cdm-level'));
            $('#edit-leave-count').val($(this).data('leave'));
            $('#edit-notes').val($(this).data('notes'));
            $('#editForm').attr('action', '/assign_leaves/' + assignId);
            $('#editModal').modal('show');
        });

        // Delete button click handler
        $('.delete-btn').click(function() {
            var assignId = $(this).data('id');
            $('#deleteForm').attr('action', '/assign_leaves/' + assignId);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endpush
