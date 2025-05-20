@extends('layouts.app')
@section('title', 'My Time Records')
@section('content')

@push('styles')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css" rel="stylesheet">
@endpush

<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">My Time Records</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white py-3 align-items-center">
            <h6 class="m-0 font-weight-bold ">Time Records</h6>
            <form method="GET" class="row g-2 align-items-center mt-2" action="">
                <div class="col-auto">
                    <label for="start_date" class="col-form-label">Start Date</label>
                </div>
                <div class="col-auto">
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-auto">
                    <label for="end_date" class="col-form-label">End Date</label>
                </div>
                <div class="col-auto">
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('time-records.my') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-3">
                {{-- <a href="{{ route('time-records.my', array_merge(request()->all(), ['report' => '1'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Generate Report
                </a> --}}
            </div>
            <div class="table-responsive">
                <table id="myTimeRecordsTable" class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timeRecords as $record)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $record->type)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($record->recorded_at)->format('h:i:s A') }}</td>
                            <td>{{ optional($record->employee)->department->name ?? 'N/A' }}</td>
                            <td>{{ optional($record->employee)->position->name ?? 'N/A' }}</td>
                            <td>{{ optional($record->employee)->agency->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($record->status) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No time records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
    const employeeName = '{{ auth()->user()->name }}'.replace(/\s+/g, '_');
    const fileName = `${employeeName}_Time_Records_${today}`;

    $('#myTimeRecordsTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center"ip>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success me-2',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: fileName,
                filename: fileName
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger me-2',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: fileName,
                filename: fileName
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: fileName
            }
        ],
        order: [[0, 'desc'], [2, 'desc']], // Sort by date and time by default
        pageLength: 25,
        language: {
            search: "Search records:",
            lengthMenu: "Show _MENU_ records per page",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)"
        },
        columnDefs: [
            {
                targets: '_all',
                searchable: true
            }
        ]
    });
});
</script>
@endpush

@endsection
