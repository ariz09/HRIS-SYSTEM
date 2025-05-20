@extends('layouts.app')
@section('title', 'All Employees Time Records')
@section('content')

@push('styles')

@endpush

<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">All Employees Time Records</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white py-3">
            <h6 class="m-0 font-weight-bold">Time Records</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
               {{--  <a href="{{ route('time-records.all', array_merge(request()->all(), ['report' => '1'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Generate Report
                </a> --}}
            </div>
            <div class="table-responsive">
                <table id="allTimeRecordsTable" class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Get records for the selected date range or today if no dates selected
                            $startDate = request('start_date') ? Carbon\Carbon::parse(request('start_date'))->startOfDay() : now()->startOfDay();
                            $endDate = request('end_date') ? Carbon\Carbon::parse(request('end_date'))->endOfDay() : now()->endOfDay();

                            $groupedRecords = $timeRecords
                                ->whereBetween('recorded_at', [$startDate, $endDate])
                                ->groupBy(['user_id', function($record) {
                                    return Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d');
                                }]);
                        @endphp
                        @forelse($groupedRecords as $userId => $dateRecords)
                            @foreach($dateRecords as $date => $records)
                                @php
                                    $employee = $records->first()->employee ?? null;
                                    $timeIn = $records->where('type', 'time_in')->sortBy('recorded_at')->first();
                                    $timeOut = $records->where('type', 'time_out')->sortByDesc('recorded_at')->first();
                                    $totalHours = '';

                                    if ($timeIn && $timeOut) {
                                        $start = Carbon\Carbon::parse($timeIn->recorded_at);
                                        $end = Carbon\Carbon::parse($timeOut->recorded_at);
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
                                    <td>{{ optional($employee->user)->name ?? 'N/A' }}</td>
                                    <td>{{ $date }}</td>
                                    <td>{{ $timeIn ? Carbon\Carbon::parse($timeIn->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                    <td>{{ $timeOut ? Carbon\Carbon::parse($timeOut->recorded_at)->format('h:i:s A') : 'N/A' }}</td>
                                    <td>{{ optional($employee)->department->name ?? 'N/A' }}</td>
                                    <td>{{ optional($employee)->position->name ?? 'N/A' }}</td>
                                    <td>{{ optional($employee)->agency->name ?? 'N/A' }}</td>
                                    <td>{{ $status }}</td>
                                    <td>{{ $totalHours }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No time records found for the selected period.</td>
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
