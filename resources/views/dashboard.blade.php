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
    .btn-block-custom .btn-records {
        color: #fff !important;
        font-weight: 500;
    }
    .btn-block-custom .btn-primary {
        background: #007bff;
        border: none;
    }
    .btn-block-custom .btn-primary:hover, .btn-block-custom .btn-primary:focus {
        background: #0056b3;
    }
    .btn-block-custom .btn-warning {
        background: #ffc107;
        border: none;
        color: #222 !important;
    }
    .btn-block-custom .btn-warning:hover, .btn-block-custom .btn-warning:focus {
        background: #e0a800;
        color: #111 !important;
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
        <h1 class="h1 mb-2 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <!-- Clock, Buttons, Table -->
        <div class="col-md-4">
            <div x-data="{ time: new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                 x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                <div class="mac-clock mb-3" x-text="time"></div>
            </div>

            <div class="btn-block-custom">
                <button class="btn btn-outline-success" id="time-in"><i class="fas fa-sign-in-alt mr-1"></i> Time-in</button>
                <button class="btn btn-outline-secondary" id="time-out"><i class="fas fa-sign-out-alt mr-1"></i> Time-out</button>
            </div>


            <div class="card shadow mt-4">
                <div class="card-header bg-danger py-3">
                    <h6 class="m-0 font-weight-bold text-white">Today's Time Transactions</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody id="time-records-body">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1 mt-1">
                    <a href="{{ route('time-records.my') }}" class="btn btn-outline-primary btn-sm w-100 btn-records mb-1 mt-1">
                        <i class="fas fa-clock mr-1"></i> My Time Records</a>
                    <a href="{{ route('time-records.all') }}" class="btn btn-outline-primary btn-sm w-100 btn-records mb-1 mt-1">
                        <i class="fas fa-users mr-1"></i> All Employees Time Records</a>
                </div>
        </div>


        <!-- Calendar -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-danger py-3">
                    <h6 class="m-0 font-weight-bold text-white">Holiday Calendar</h6>
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
            // Restore button after processing
            timeOutBtn.disabled = true;
            timeOutBtn.innerHTML = '<i class="fas fa-sign-out-alt mr-1"></i> Time-out';
            timeOutBtn.classList.remove('btn-warning', 'disabled');
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

            // Get the earliest time_in and latest time_out
            const timeInRecord = todayRecords
                .filter(r => r.type === 'time_in')
                .sort((a, b) => new Date(a.recorded_at) - new Date(b.recorded_at))[0];

            const timeOutRecord = todayRecords
                .filter(r => r.type === 'time_out')
                .sort((a, b) => new Date(b.recorded_at) - new Date(a.recorded_at))[0];

            // Time-In button: disable if already timed in (regardless of status)
            timeInBtn.disabled = !!timeInRecord;

            // Time-Out button: enable if Time-In exists (regardless of status) and no Time-Out exists
            timeOutBtn.disabled = !(timeInRecord) || !!timeOutRecord;

            if (!timeInRecord && !timeOutRecord) {
                recordsBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">No time records today</td>
                    </tr>
                `;
                return;
            }

            // Calculate total hours if both records exist
            let totalHours = 'N/A';
            if (timeInRecord && timeOutRecord) {
                const start = new Date(timeInRecord.recorded_at);
                const end = new Date(timeOutRecord.recorded_at);
                if (end > start) {
                    const diffInMinutes = Math.floor((end - start) / (1000 * 60));
                    const hours = Math.floor(diffInMinutes / 60);
                    const minutes = diffInMinutes % 60;
                    totalHours = `${hours}:${minutes.toString().padStart(2, '0')}`;
                }
            }

            function formatTime(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
            }

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formatTime(timeInRecord?.recorded_at)}</td>
                <td>${formatTime(timeOutRecord?.recorded_at)}</td>
                <td>${timeOutRecord ? timeOutRecord.status : (timeInRecord ? timeInRecord.status : 'N/A')}</td>
                <td>${totalHours}</td>
            `;
            recordsBody.appendChild(row);

            // Update button states based on records
            if (timeInRecord) {
                timeInBtn.disabled = true;
                timeInBtn.classList.add('disabled');
            }
            if (timeOutRecord) {
                timeOutBtn.disabled = true;
                timeOutBtn.classList.add('disabled');
            } else if (timeInRecord) {
                timeOutBtn.disabled = false;
                timeOutBtn.classList.remove('disabled');
            }
        } catch (error) {
            console.error('Failed to load time records:', error);
            showAlert('warning', 'Failed to load time records');
            recordsBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center text-danger">Failed to load records</td>
                </tr>
            `;
        }
    }

    // Event listeners
    document.getElementById('time-in').addEventListener('click', handleTimeIn);
    document.getElementById('time-out').addEventListener('click', () => {
        timeOutModal.show();
        confirmTimeOutBtn.addEventListener('click', handleTimeOut);
    });

    // Initialize calendar
    const events = JSON.parse(`{!! addslashes(json_encode($events)) !!}`);
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
