@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Holiday Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Holidays</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-1"></i>
                    Holiday List
                </div>
                <div>
                    <button type="button" class="btn btn-light text-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                        <i class="fas fa-plus"></i> Add Holiday
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="holidaysTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="d-none">#</th>
                            <th>Holiday Name</th>
                            <th>Holiday Date</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                        <tr>
                            <td class="d-none">{{ $loop->iteration }}</td>
                            <td>{{ $holiday->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($holiday->date)->format('F j, Y') }}</td>
                            <td>{{ ucfirst($holiday->status) }}</td>
                            <td>{{ ucfirst($holiday->type) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editHolidayModal{{ $holiday->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal{{ $holiday->id }}">
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

<!-- Add Holiday Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('holidays.store') }}" method="POST">
                @csrf
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="addHolidayModalLabel">Add New Holiday</h5>
                    <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Holiday Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Holiday Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Holiday Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="regular">Regular</option>
                            <option value="special">Special</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Add Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
@foreach ($holidays as $holiday)
<div class="modal fade" id="editHolidayModal{{ $holiday->id }}" tabindex="-1" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                    <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Holiday Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $holiday->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Holiday Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $holiday->date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" {{ $holiday->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $holiday->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Holiday Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="regular" {{ $holiday->type == 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="special" {{ $holiday->type == 'special' ? 'selected' : '' }}>Special</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Delete Modal -->
@foreach ($holidays as $holiday)
<div class="modal fade" id="deleteHolidayModal{{ $holiday->id }}" tabindex="-1" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header  bg-danger text-white">
                    <h5 class="modal-title" id="deleteHolidayModalLabel">Delete Holiday</h5>
                    <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the holiday "{{ $holiday->name }}"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

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
    $(document).ready(function() {
        // Initialize DataTable with responsive plugin
        $('#holidaysTable').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details for ' + data[0]; // Holiday Name
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            }
        });
    });
</script>
@endpush
