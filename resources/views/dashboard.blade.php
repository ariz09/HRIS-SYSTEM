@extends('layouts.app')

@section('title', 'HRIS Dashboard')

@section('content')

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
</style>
@endpush

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap">
        <h1 class="h1 mb-2 text-gray-800">My Dashboard</h1>
    </div>

    <div class="row">
        <!-- Timer + Buttons + Table -->
        <div class="col-md-4">
            <div x-data="{ time: new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                 x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                <div class="mac-clock mb-3" x-text="time"></div>
            </div>

            <div class="btn-block-custom">
                @if($canTimeIn)
                    <button class="btn btn-success" id="time-in"><i class="fas fa-sign-in-alt mr-1"></i> Time In</button>
                @endif
                @if($canTimeOut)
                    <button class="btn btn-danger" id="time-out"><i class="fas fa-sign-out-alt mr-1"></i> Time Out</button>
                @endif
            </div>

            @if($statusMessage)
                <div class="alert alert-info mt-3">
                    {{ $statusMessage }}
                </div>
            @endif

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Today's Time Transactions</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="time-records-body">
                                @if($timeRecords && $timeRecords->count())
                                    @foreach($timeRecords as $record)
                                        <tr>
                                            <td>{{ $record->user->name }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $record->type)) }}</td>
                                            <td>{{ is_string($record->recorded_at) ? $record->recorded_at : $record->recorded_at->format('H:i:s') }}</td>
                                            <td>{{ ucfirst($record->status) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No time transactions yet.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Holiday Calendar -->
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
        console.log('DOM Content Loaded'); // Debug log

        // Set up axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const events = @json($events); // Pass the events as JSON

        var calendar = new FullCalendar.Calendar(document.getElementById('holiday-calendar'), {
            initialView: 'dayGridMonth',
            events: events.map(event => ({
                title: event.title,
                start: event.start,  // Ensure the dates are correctly formatted
                backgroundColor: '#e74a3b',
                borderColor: '#e74a3b',
                textColor: 'white',
            })),
            editable: false,
            droppable: false,
            eventClick: function(info) {
                alert('Holiday: ' + info.event.title);
            },
        });

        calendar.render();  // Render the calendar

        // Time In/Out functionality
        const timeInBtn = document.getElementById('time-in');
        const timeOutBtn = document.getElementById('time-out');
        const timeRecordsBody = document.getElementById('time-records-body');
        const btnBlockCustom = document.querySelector('.btn-block-custom');

        console.log('Time In Button:', timeInBtn); // Debug log
        console.log('Time Out Button:', timeOutBtn); // Debug log

        function updateTimeRecords(records) {
            console.log('Updating time records:', records); // Debug log
            timeRecordsBody.innerHTML = records.length ? records.map(record => `
                <tr>
                    <td>${record.user.name}</td>
                    <td>${record.type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</td>
                    <td>${new Date(record.recorded_at).toLocaleTimeString()}</td>
                    <td>${record.status.charAt(0).toUpperCase() + record.status.slice(1)}</td>
                </tr>
            `).join('') : `
                <tr>
                    <td colspan="4" class="text-center text-muted">No time transactions yet.</td>
                </tr>
            `;
        }

        function updateButtons(canTimeIn, canTimeOut) {
            console.log('Updating buttons:', { canTimeIn, canTimeOut }); // Debug log
            btnBlockCustom.innerHTML = '';

            if (canTimeIn) {
                btnBlockCustom.innerHTML += `
                    <button class="btn btn-success" id="time-in">
                        <i class="fas fa-sign-in-alt mr-1"></i> Time In
                    </button>
                `;
                const newTimeInBtn = document.getElementById('time-in');
                if (newTimeInBtn) {
                    newTimeInBtn.addEventListener('click', () => {
                        console.log('Time In button clicked'); // Debug log
                        handleTimeAction('time_in');
                    });
                }
            }

            if (canTimeOut) {
                btnBlockCustom.innerHTML += `
                    <button class="btn btn-danger" id="time-out">
                        <i class="fas fa-sign-out-alt mr-1"></i> Time Out
                    </button>
                `;
                const newTimeOutBtn = document.getElementById('time-out');
                if (newTimeOutBtn) {
                    newTimeOutBtn.addEventListener('click', () => {
                        console.log('Time Out button clicked'); // Debug log
                        handleTimeAction('time_out');
                    });
                }
            }
        }

        function handleTimeAction(action) {
            console.log('Handling time action:', action); // Debug log
            axios.post('/dashboard/action', { action })
                .then(response => {
                    console.log('Server response:', response.data); // Debug log
                    if (response.data.message) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${response.data.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));

                        // Force reload the page immediately
                        window.location.href = window.location.href;
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Debug log
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        ${error.response?.data?.message || 'An error occurred while processing your request.'}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row'));

                    // Auto dismiss after 3 seconds
                    setTimeout(() => alertDiv.remove(), 3000);
                });
        }

        // Initial button setup
        if (timeInBtn) {
            console.log('Adding click listener to Time In button'); // Debug log
            timeInBtn.addEventListener('click', () => {
                console.log('Time In button clicked'); // Debug log
                handleTimeAction('time_in');
            });
        }
        if (timeOutBtn) {
            console.log('Adding click listener to Time Out button'); // Debug log
            timeOutBtn.addEventListener('click', () => {
                console.log('Time Out button clicked'); // Debug log
                handleTimeAction('time_out');
            });
        }
    });
</script>
@endpush

@endsection
