@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">My Time Records Today</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function loadTimeRecords() {
        axios.get("{{ route('time-records.index') }}")
            .then(response => {
                const tbody = document.getElementById('time-records-body');
                tbody.innerHTML = '';

                const records = response.data;

                if (records.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-muted">No records yet today.</td>
                        </tr>
                    `;
                    return;
                }

                records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.type.replace('_', ' ')}</td>
                        <td>${new Date(record.recorded_at).toLocaleTimeString()}</td>
                        <td>${record.status}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading time records:', error);
                alert('Failed to load time records.');
            });
    }

    loadTimeRecords();
});
</script>
@endpush
