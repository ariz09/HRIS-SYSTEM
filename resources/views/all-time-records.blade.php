@extends('layouts.app')
@section('title', "All Employees' Time Records")
@section('content')
<div class="container py-4">
    <h2 class="mb-4">All Employees' Time Records</h2>
    @include('components.all-time-records-table')
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const allTimeRecordsTableBody = document.querySelector('#all-time-records-table tbody');
    allTimeRecordsTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>';
    axios.get("/api/all-time-records").then(response => {
        if (response.data && response.data.length > 0) {
            allTimeRecordsTableBody.innerHTML = '';
            response.data.forEach(record => {
                const date = new Date(record.recorded_at);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.user_name || record.employee_name || 'N/A'}</td>
                    <td>${date.toLocaleDateString()}</td>
                    <td>${record.type.replace('_', ' ')}</td>
                    <td>${date.toLocaleTimeString()}</td>
                    <td>${record.status}</td>
                `;
                allTimeRecordsTableBody.appendChild(row);
            });
        } else {
            allTimeRecordsTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No time records found.</td></tr>';
        }
    }).catch(() => {
        allTimeRecordsTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load records.</td></tr>';
    });
});
</script>
@endpush
@endsection
