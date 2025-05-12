@extends('layouts.app')
@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :message="session('error')" />
<div class="container">
    <h3>Edit Employee</h3>

   <form method="POST" action="{{ route('employees.update', $employee->id) }}">
        @csrf
        @method('PUT')
        @include('employees._form')
        <button type="submit" class="btn btn-primary">Update Employee</button>
    </form>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CDM level change handler
    const cdmSelect = document.getElementById('cdm_level_id');
    if (cdmSelect) { // Check if element exists
        cdmSelect.addEventListener('change', function () {
            const selectedCDMLevelId = this.value;
            const positionSelect = document.getElementById('position_id');
            
            // Reset to default option immediately
            positionSelect.innerHTML = '<option value="">Select Position</option>';
            positionSelect.value = '';
            
            if (!selectedCDMLevelId) return;
            
            // Show loading state
            positionSelect.innerHTML = '<option value="">Loading positions...</option>';
            
            fetch('/positions?cdm_level_id=' + selectedCDMLevelId)
                .then(response => response.json())
                .then(data => {
                    positionSelect.innerHTML = '<option value="">Select Position</option>';
                    data.positions.forEach(function (position) {
                        const option = new Option(position.name, position.id);
                        positionSelect.add(option);
                    });
                    positionSelect.value = '';
                })
                .catch(error => {
                    console.error('Error fetching positions:', error);
                    positionSelect.innerHTML = '<option value="">Error loading positions</option>';
                });
        });

        // Trigger initial load if value exists
        if (cdmSelect.value) {
            cdmSelect.dispatchEvent(new Event('change'));
        }
    } else {
        console.error('CDM Level select element not found');
    }
});
</script>
