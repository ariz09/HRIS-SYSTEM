@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employment Type Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Employment Types</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header  bg-danger text-white d-flex justify-content-between align-items-center">
            <div><i class="fas fa-table me-1"></i> Employment Type List</div>
            <div>
                <button type="button" class="btn btn-light text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Add Employment Type
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="employmentTypesTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th>Employment Type Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employmentTypes as $employmentType)
                        <tr>
                            <td class="d-none">{{ $loop->iteration }}</td>
                            <td>{{ $employmentType->name }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $employmentType->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $employmentType->id }}">
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
    <form method="POST" action="{{ route('employment_types.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header  bg-danger text-white">
          <h5 class="modal-title" id="createModalLabel">Add Employment Type</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-success">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header  bg-danger text-white">
          <h5 class="modal-title" id="editModalLabel">Edit Employment Type</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit-name" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header  bg-danger text-white">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this employment type?
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
@section('scripts')
@push('scripts')

<script>
    $(document).ready(function() {
      setDatatable("employmentTypesTable", {
            dom: 'rtip', // minimal layout
            buttons: [] // no export buttons
        }); 
        
        // Edit button
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            $.get('/employment_types/' + id + '/edit', function(response) {
                $('#edit-name').val(response.employmentType.name);
                $('#editForm').attr('action', '/employment_types/' + id);
                $('#editModal').modal('show');
            }).fail(function() {
                alert('Error fetching data.');
            });
        });

        // Delete button
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            $('#deleteForm').attr('action', '/employment_types/' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endpush
