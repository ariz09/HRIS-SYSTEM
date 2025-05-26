@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">My Leave Requests</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Leave Requests</li>
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
                Leave Requests
            </div>
            <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Submit New Request
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
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
                                <td>{{ Str::limit($leave->reason, 50) }}</td>
                                <td>
                                    @switch($leave->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('leaves.show', $leave->id) }}"
                                           class="btn btn-info btn-sm"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($leave->status === 'pending')
                                            <a href="{{ route('leaves.edit', $leave->id) }}"
                                               class="btn btn-warning btn-sm"
                                               title="Edit Request">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('leaves.destroy', $leave->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to cancel this leave request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Cancel Request">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No leave requests found.</td>
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
    // Auto-hide success alert after 5 seconds
    setTimeout(function() {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            successAlert.style.display = 'none';
        }
    }, 5000);
</script>
@endpush
@endsection
