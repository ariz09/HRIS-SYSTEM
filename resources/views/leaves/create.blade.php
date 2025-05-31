@extends('layouts.app')
@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h5>Your Information</h5>
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Position:</strong> {{ $position }}
            </li>
            <li class="list-group-item">
                <strong>CDM Level:</strong> {{ $cdmLevel }}
            </li>
            @foreach ($entitlementModels as $entitlement)
                <p><strong>{{ $entitlement->leaveType->name }}</strong>: {{ $entitlement->days_allowed }} days</p>
            @endforeach
        </ul>
    </div>

    <h1 class="mt-4">Submit Leave Request</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Submit Leave Request</li>
    </ol>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($balances)
        <div class="mb-4">
            <h5>Leave Balances</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Leave Type</th>
                            <th>Allowed</th>
                            <th>Used</th>
                            <th>Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>{{ $balances[$type->id]['allowed'] }}</td>
                                <td>{{ $balances[$type->id]['used'] }}</td>
                                <td>{{ $balances[$type->id]['remaining'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-calendar-plus me-1"></i>
            New Leave Request
        </div>
        <div class="card-body">
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <select class="form-select" id="leave_type" name="leave_type_id" required>
                            <option value="">Select Leave Type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <div id="leave-balance-info" class="mt-2 text-info small"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="duration" class="form-label">Duration</label>
                        <select class="form-select" id="duration" name="duration" required>
                            <option value="full_day">Full Day</option>
                            <option value="half_day_morning">Half Day (Morning)</option>
                            <option value="half_day_afternoon">Half Day (Afternoon)</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Leave</label>
                    <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number During Leave</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                </div>

                <div class="mb-3">
                    <label for="address_during_leave" class="form-label">Address During Leave</label>
                    <textarea class="form-control" id="address_during_leave" name="address_during_leave" rows="2" required></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
document.getElementById('end_date').addEventListener('change', function() {
    document.getElementById('start_date').max = this.value;
});
const balances = @json($balances);
const entitlements = @json($entitlements);
const leaveTypeSelect = document.getElementById('leave_type');
const balanceInfo = document.getElementById('leave-balance-info');
let allowedInfo = document.getElementById('leave-allowed-info');
if (!allowedInfo) {
    allowedInfo = document.createElement('div');
    allowedInfo.id = 'leave-allowed-info';
    allowedInfo.className = 'mt-1 text-success small';
    leaveTypeSelect.parentNode.appendChild(allowedInfo);
}
leaveTypeSelect.addEventListener('change', function() {
    const id = this.value;
    if (balances[id]) {
        balanceInfo.textContent = `Remaining: ${balances[id].remaining} day(s) (Used: ${balances[id].used} / Allowed: ${balances[id].allowed})`;
    } else {
        balanceInfo.textContent = '';
    }
    if (entitlements[id]) {
        allowedInfo.textContent = `Days Allowed for your level: ${entitlements[id].days_allowed ?? 0} day(s)`;
    } else {
        allowedInfo.textContent = '';
    }
});
</script>
@endpush
