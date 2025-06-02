@extends('layouts.app')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">My Leave Requests</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">My Leave Requests</li>
    </ol>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar me-1"></i>
                My Leave Requests
            </div>
            <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Leave Request
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->leaveType->name }}</td>
                                <td>
                                    @switch($leave->duration)
                                        @case('full_day')
                                            Full Day
                                            @break
                                        @case('half_day_morning')
                                            Half Day (Morning)
                                            @break
                                        @case('half_day_afternoon')
                                            Half Day (Afternoon)
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'declined' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('leaves.show', $leave->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No leave requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
