@extends('layouts.app')
@section('title', 'My Time Records')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">My Time Records</h2>
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="start-date" class="form-label">Start Date</label>
            <input type="date" id="start-date" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="end-date" class="form-label">End Date</label>
            <input type="date" id="end-date" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button id="filter-time-records" class="btn btn-primary">Filter</button>
            <button id="reset-time-records" class="btn btn-secondary ms-2">Reset</button>
        </div>
    </div>
    @include('components.my-time-records-table')
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let myTimeRecordsCache = [];
    document.getElementById('my-time-records-table').querySelector('tbody').innerHTML = '<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>';
    axios.get("{{ route('time-records.index') }}").then(response => {
        myTimeRecordsCache = response.data || [];
        renderMyTimeRecordsTable(myTimeRecordsCache);
    }).catch(() => {
        document.getElementById('my-time-records-table').querySelector('tbody').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Failed to load records.</td></tr>';
    });
    function renderMyTimeRecordsTable(records) {
        const tbody = document.getElementById('my-time-records-table').querySelector('tbody');
        tbody.innerHTML = '';
        if (!records || records.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No time records found.</td></tr>';
            return;
        }
        records.forEach(record => {
            const date = new Date(record.recorded_at);
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${date.toLocaleDateString()}</td>
                <td>${record.type.replace('_', ' ')}</td>
                <td>${date.toLocaleTimeString()}</td>
                <td>${record.status}</td>
            `;
            tbody.appendChild(row);
        });
    }
    document.getElementById('filter-time-records').addEventListener('click', function() {
        renderMyTimeRecordsTable(myTimeRecordsCache);
    });
});
</script>
@endpush
@endsection
