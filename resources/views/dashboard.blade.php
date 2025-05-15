@extends('layouts.app')
@section('title', 'HRIS Dashboard')
@section('content')

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Time-Out</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to time out and log your session?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirm-time-out" type="button" class="btn btn-danger">Yes, Time Out</button>
      </div>
    </div>
  </div>
</div>

<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .fc {
        font-size: 0.9rem;
    }
    .fc-toolbar-title {
        font-weight: 600;
        font-size: 1.25rem;
    }
    #holiday-calendar {
        min-height: 500px;
    }
    .fc-event-ph {
        background-color: #e74a3b !important;
        border-color: #e74a3b !important;
        color: white !important;
        font-weight: 500;
    }
    .mac-clock {
        font-size: 3rem;
        font-weight: bold;
        text-align: center;
        color: #333;
        background: #f8f9fc;
        padding: 30px;
        border-radius: 20px;
        box-shadow: inset 0 0 20px #ccc;
        font-family: 'Courier New', Courier, monospace;
    }
    .btn-block-custom {
        display: flex;
        justify-content: space-around;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .btn-block-custom .btn {
        flex: 1 1 30%;
        font-size: 1rem;
        padding: 15px;
    }
    .alert {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideIn 0.5s forwards;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>
@endpush

<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">My Dashboard</h1>
    </div>

    <div class="row">
        <!-- Clock, Buttons, Table -->
        <div class="col-md-4">
            <div x-data="{ time: new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                 x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                <div class="mac-clock mb-3" x-text="time"></div>
            </div>

            <div class="btn-block-custom">
                <button class="btn btn-success" id="time-in"><i class="fas fa-sign-in-alt mr-1"></i> Time-in</button>
                <button class="btn btn-danger" id="time-out"><i class="fas fa-sign-out-alt mr-1"></i> Time-out</button>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Today's Time Transactions</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="time-records-body">
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           <a href="{{ route('time-records.my') }}" class="btn btn-info mt-3">My Time Records</a>
           <a href="{{ route('time-records.all') }}" class="btn btn-secondary mt-3">All Employees Time Records</a>

        </div>

        <!-- Calendar -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Holiday Calendar</h6>
                </div>
                <div class="card-body">
                    <div id="holiday-calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize elements
    const timeInBtn = document.getElementById('time-in');
    const timeOutBtn = document.getElementById('time-out');
    const confirmTimeOutBtn = document.getElementById('confirm-time-out');
    const timeOutModal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));

    // Set CSRF token
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
    axios.defaults.headers.common['Accept'] = 'application/json';

    // State management
    let isProcessing = false;

    // Improved alert function
    function showAlert(type, message) {
        // Remove existing alerts first
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getInstance(alert);
            if (bsAlert) bsAlert.close();
        });

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.style.position = 'fixed';
        alert.style.top = '70px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.style.maxWidth = '400px';
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);

        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    }

    // Time-in handler
    async function handleTimeIn() {
        if (isProcessing) return;
        isProcessing = true;
        timeInBtn.disabled = true;
        // Add animation to indicate processing
        timeInBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
        timeInBtn.classList.add('disabled', 'btn-warning');

        try {
            const response = await axios.post('{{ route("time-in") }}');

            if (response.data.success) {
                showAlert('success', 'Time-In recorded successfully!');
                timeOutBtn.disabled = false; // Enable Time-Out button after successful Time-In
                await loadTimeRecords();
            } else {
                showAlert('warning', response.data.message || 'Time-In failed. Please try again.');
            }
        } catch (error) {
            const errorMsg = error.response?.data?.message ||
                           error.response?.data?.error ||
                           'An error occurred during Time-In';
            showAlert('danger', errorMsg);
        } finally {
            isProcessing = false;
            // Restore button after processing
            timeInBtn.disabled = !!document.querySelector('#time-records-body tr td:first-child')?.textContent.toLowerCase().includes('time in');
            timeInBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-1"></i> Time-in';
            timeInBtn.classList.remove('btn-warning', 'disabled');
        }
    }

    // Time-out handler
    async function handleTimeOut() {
        if (isProcessing) return;
        isProcessing = true;
        confirmTimeOutBtn.disabled = true;

        try {
            const response = await axios.post('{{ route("dashboard.action") }}', { action: 'time_out' });

            if (response.data.success) {
                showAlert('success', 'Time-Out recorded successfully!');
                // Force full reload to ensure consistent state
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('warning', response.data.message || 'Time-Out failed. Please try again.');
            }
        } catch (error) {
            const errorMsg = error.response?.data?.message ||
                           error.response?.data?.error ||
                           'An error occurred during Time-Out';
            showAlert('danger', errorMsg);
        } finally {
            isProcessing = false;
            confirmTimeOutBtn.disabled = false;
            timeOutModal.hide();
        }
    }

    // Load time records
    async function loadTimeRecords() {
    try {
        const response = await axios.get('{{ route("time-records.index") }}');

        const recordsBody = document.getElementById('time-records-body');
        recordsBody.innerHTML = '';

        const today = new Date().toISOString().split('T')[0];
        const todayRecords = response.data.filter(record =>
            record.recorded_at.startsWith(today)
        );

        const timeInRecord = todayRecords.find(r => r.type === 'time_in');
        const timeOutRecord = todayRecords.find(r => r.type === 'time_out');

        // Time-In button: disable if already timed in (regardless of status)
        timeInBtn.disabled = !!timeInRecord;

        // Time-Out button: enable if Time-In exists (regardless of status) and no Time-Out exists
        timeOutBtn.disabled = !(timeInRecord) || !!timeOutRecord;

        if (todayRecords.length === 0) {
            recordsBody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-muted">No time records today</td>
                </tr>
            `;
            return;
        }

        todayRecords.forEach(record => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${record.type.replace('_', ' ')}</td>
                <td>${new Date(record.recorded_at).toLocaleTimeString()}</td>
                <td>${record.status}</td>
            `;
            recordsBody.appendChild(row);
        });
    } catch (error) {
        console.error('Failed to load time records:', error);
        showAlert('warning', 'Failed to load time records');
    }
}


    // Event listeners
    timeInBtn.addEventListener('click', handleTimeIn);

    timeOutBtn.addEventListener('click', () => {
        const alreadyOut = [...document.querySelectorAll('#time-records-body tr td:first-child')]
            .some(td => td.textContent.trim().toLowerCase().includes('time out'));

        if (alreadyOut) {
            showAlert('info', 'You have already timed out today');
        } else {
            timeOutModal.show();
        }
    });

    confirmTimeOutBtn.addEventListener('click', handleTimeOut);

    // Initialize calendar
    const events = @json($events);
    new FullCalendar.Calendar(document.getElementById('holiday-calendar'), {
        initialView: 'dayGridMonth',
        events: events.map(event => ({
            title: event.title,
            start: event.start,
            backgroundColor: '#e74a3b',
            borderColor: '#e74a3b',
            textColor: 'white'
        })),
        editable: false,
        eventClick: info => alert('Holiday: ' + info.event.title)
    }).render();

    // Initial load
    loadTimeRecords();
});
</script>
@endpush

@endsection
