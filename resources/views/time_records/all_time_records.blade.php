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
        <form method="GET" action="" class="p-3 bg-light border rounded shadow-sm mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label text-dark">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label text-dark">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('time-records.all') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
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
                            <th>Total Working Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Get records for the selected date range or today if no dates selected
                            $startDate = request('start_date') ? Carbon\Carbon::parse(request('start_date'))->startOfDay() : now()->startOfDay();
                            $endDate = request('end_date') ? Carbon\Carbon::parse(request('end_date'))->endOfDay() : now()->endOfDay();

                            $groupedRecords = $timeRecords
                                ? $timeRecords->whereBetween('recorded_at', [$startDate, $endDate])
                                            ->groupBy(['user_id', function($record) {
                                                return Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d');
                                            }])
                                : [];
                        @endphp
                        @forelse($groupedRecords as $userId => $dateRecords)
                            @foreach($dateRecords as $date => $records)
                                @php
                                    $employee = $records->first()->employee ?? null;
                                    $timeIn = $records->where('type', 'time_in')->sortBy('recorded_at')->first();
                                    $timeOut = $records->where('type', 'time_out')->sortByDesc('recorded_at')->first();
                                    $totalHours = '';

                                    if ($timeIn && $timeOut) {
                                        $start = Carbon\Carbon::parse($timeIn->recorded_at)->setTimezone(config('app.timezone'))->locale('en');
                                        $end = Carbon\Carbon::parse($timeOut->recorded_at)->setTimezone(config('app.timezone'))->locale('en');

                                        // Only calculate if end time is after start time
                                        if ($end->greaterThan($start)) {
                                            // Calculate total minutes
                                            $diffInMinutes = $start->diffInMinutes($end);

                                            // Handle overnight shifts (if end time is before start time on the same day)
                                            if ($end->format('Y-m-d') === $start->format('Y-m-d') && $end->lt($start)) {
                                                $diffInMinutes = 24 * 60 - $diffInMinutes;
                                            }

                                            // Convert to hours and minutes
                                            $hours = floor($diffInMinutes / 60);
                                            $minutes = $diffInMinutes % 60;

                                            // Format with leading zeros for minutes
                                            $totalHours = sprintf('%d:%02d', $hours, $minutes);

                                            // Add warning for unusually long shifts (>12 hours)
                                            if ($hours > 12) {
                                                $totalHours .= ' <span class="text-warning" title="Unusually long shift">⚠️</span>';
                                            }
                                        } else {
                                            $totalHours = 'Invalid';
                                        }
                                    } elseif ($timeIn) {
                                        $totalHours = 'No Time Out';
                                    } elseif ($timeOut) {
                                        $totalHours = 'No Time In';
                                    } else {
                                        $totalHours = 'N/A';
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
                                    <td>{{ $date ?? 'N/A'}}</td>
                                    <td>{{ $timeIn ? Carbon\Carbon::parse($timeIn->recorded_at)->setTimezone(config('app.timezone'))->locale('en')->format('g:i:s A') : 'N/A' }}</td>
                                    <td>{{ $timeOut ? Carbon\Carbon::parse($timeOut->recorded_at)->setTimezone(config('app.timezone'))->locale('en')->format('g:i:s A') : 'N/A' }}</td>
                                    <td>{{ optional($employee)->department->name ?? 'N/A' }}</td>
                                    <td>{{ optional($employee)->position->name ?? 'N/A' }}</td>
                                    <td>{{ optional($employee)->agency->name ?? 'N/A' }}</td>
                                    <td>{{ $status ?? 'N/A' }}</td>
                                    <td>{{ $totalHours ?? 'N/A' }}</td>
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
    const table = $('#allTimeRecordsTable');
    
    // Only initialize if table exists and has valid structure
    if (table.length && table.find('thead th').length === table.find('tbody tr:first td').length) {
        const today = new Date().toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit' 
        }).replace(/\//g, '-');
        
        const fileName = `All_Employees_Time_Records_${today}`;
        
        table.DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center"ip>',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success me-2',
                    title: fileName,
                    filename: fileName
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger me-2',
                    title: fileName,
                    filename: fileName
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-info',
                    title: fileName
                }
            ],
            order: [[1, 'desc']],
            pageLength: 25,
            language: {
                emptyTable: "No records available",
                zeroRecords: "No matching records found"
            },
            initComplete: function() {
                // Safe initialization callback
                console.log('DataTables initialized successfully');
            },
            error: function() {
                console.error('DataTables initialization error');
            }
        });
    } else {
        console.warn('DataTables not initialized - table structure invalid');
        table.addClass('table-bordered table-striped');
    }
});
</script>
@endpush

@endsection
