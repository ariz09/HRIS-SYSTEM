@extends('layouts.app')
@section('title', 'My Time Records')
@section('content')

@push('styles')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap5.min.css" rel="stylesheet">
<style>
    .custom-table thead th {
        background-color: #f5f5f5 !important;
        color: #333 !important;
        border-bottom: 2px solid #dee2e6 !important;
        font-weight: 600;
    }
    .custom-table {
        border: 1px solid #dee2e6;
    }
</style>
@endpush

<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">My DTR Report</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header bg-white text-dark py-3 align-items-center d-flex justify-content-between flex-wrap">
            <h6 class="m-0 font-weight-bold ">Time Records</h6>
            <form method="GET" class="row g-2 align-items-center mt-2 ms-auto" action="">
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
            <div class="table-responsive">
                <table id="myTimeRecordsTable" class="table table-bordered table-striped custom-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Total Working Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedRecords = $timeRecords->groupBy(function($record) {
                                return \Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d');
                            });
                        @endphp
                        @forelse($groupedRecords as $date => $records)
                            @php
                                // Get the first time-in and last time-out for the day
                                $timeIn = $records->where('type', 'time_in')->sortBy('recorded_at')->first();
                                $timeOut = $records->where('type', 'time_out')->sortByDesc('recorded_at')->first();
                                $employee = $records->first()->employee ?? null;
                                $totalHours = '';

                                if ($timeIn && $timeOut) {
                                    $start = \Carbon\Carbon::parse($timeIn->recorded_at);
                                    $end = \Carbon\Carbon::parse($timeOut->recorded_at);
                                    if ($end->greaterThan($start)) {
                                        $diffInMinutes = $start->diffInMinutes($end);
                                        $hours = floor($diffInMinutes / 60);
                                        $minutes = $diffInMinutes % 60;
                                        $totalHours = $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
                                    } else {
                                        $totalHours = 'N/A';
                                    }
                                }

                                // Get the status based on the latest record
                                $status = 'N/A';
                                if ($timeOut) {
                                    $status = ucfirst($timeOut->status);
                                } elseif ($timeIn) {
                                    $status = ucfirst($timeIn->status);
                                }
                            @endphp
                            <tr>
                                <td>{{ $date }}</td>
                                <td>{{ $timeIn ? \Carbon\Carbon::parse($timeIn->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                <td>{{ $timeOut ? \Carbon\Carbon::parse($timeOut->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                <td>{{ optional($employee)->department->name ?? 'N/A' }}</td>
                                <td>{{ optional($employee)->position->name ?? 'N/A' }}</td>
                                <td>{{ optional($employee)->agency->name ?? 'N/A' }}</td>
                                <td>{{ $status }}</td>
                                <td>{{ $totalHours }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No time records found.</td>
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
        order: [[0, 'desc']], // Sort by date by default
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
