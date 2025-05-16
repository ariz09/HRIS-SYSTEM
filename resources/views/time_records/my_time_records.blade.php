@extends('layouts.app')
@section('title', 'My Time Records')
@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">My Time Records</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Time Records</h6>
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
                <a href="{{ route('time-records.my', array_merge(request()->all(), ['report' => '1'])) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Generate Report
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
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
@endsection
