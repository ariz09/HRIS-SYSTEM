@extends('layouts.app')
@section('title', 'All Employees Time Records')
@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">All Employees Time Records</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Time Records</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('time-records.all', array_merge(request()->all(), ['report' => '1'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Generate Report
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timeRecords as $record)
                        <tr>
                            <td>{{ $record->employee && $record->employee->user ? $record->employee->user->name : 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $record->type)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($record->recorded_at)->format('h:i:s A') }}</td>
                            <td>{{ ucfirst($record->status) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No time records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
