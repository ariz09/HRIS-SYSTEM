@extends('layouts.app')
@section('title', 'All Employees Time Records')
@section('content')

@push('styles')
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
        <h1 class="h1 mb-2 text-gray-800">General DTR Report</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header bg-white text-dark py-3 d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold">Time Records</h6>
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
                    <a href="{{ route('time-records.all') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-3">
               {{--  <a href="{{ route('time-records.all', array_merge(request()->all(), ['report' => '1'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Generate Report
                </a> --}}
            </div>
            <div class="table-responsive">
                <table id="allTimeRecordsTable" class="table table-bordered table-striped custom-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
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
                            $today = now()->format('Y-m-d');
                            $grouped = $timeRecords->where('recorded_at', '>=', $today.' 00:00:00')
                                ->where('recorded_at', '<=', $today.' 23:59:59')
                                ->groupBy('user_id');
                        @endphp
                        @forelse($grouped as $userId => $records)
                            @php
                                $employee = $records->first()->employee ?? null;
                                $timeIn = $records->where('type', 'time_in')->sortBy('recorded_at')->first();
                                $timeOut = $records->where('type', 'time_out')->sortByDesc('recorded_at')->first();
                                $totalHours = '';
                                if ($timeIn && $timeOut) {
                                    $start = \Carbon\Carbon::parse($timeIn->recorded_at);
                                    $end = \Carbon\Carbon::parse($timeOut->recorded_at);
                                    $totalHours = $end->greaterThan($start)
                                        ? $start->diffInHours($end) . ':' . str_pad($start->diffInMinutes($end) % 60, 2, '0', STR_PAD_LEFT)
                                        : 'N/A';
                                }
                            @endphp
                            <tr>
                                <td>{{ optional($employee->user)->name ?? 'N/A' }}</td>
                                <td>{{ $today }}</td>
                                <td>{{ $timeIn ? \Carbon\Carbon::parse($timeIn->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                <td>{{ $timeOut ? \Carbon\Carbon::parse($timeOut->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                <td>{{ optional($employee)->department->name ?? 'N/A' }}</td>
                                <td>{{ optional($employee)->position->name ?? 'N/A' }}</td>
                                <td>{{ optional($employee)->agency->name ?? 'N/A' }}</td>
                                <td>{{ $timeOut ? ucfirst($timeOut->status) : ($timeIn ? ucfirst($timeIn->status) : 'N/A') }}</td>
                                <td>{{ $totalHours }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No time records found for today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')


<script>
$(document).ready(function() {
    const today = new Date().toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-');
    const fileName = `All_Employees_Time_Records_${today}`;

    $('#allTimeRecordsTable').DataTable({
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
        order: [[1, 'desc'], [3, 'desc']], // Sort by date and time by default
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
