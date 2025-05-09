@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.edit', $employee->id) }}">Employee Modules</a></li>
        <li class="breadcrumb-item active">Compensation</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-money-bill-wave me-1"></i> Compensation
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update.compensation', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Basic Pay</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="basic_pay" class="form-control" step="0.01" 
                                   value="{{ old('basic_pay', optional($employee->compensation)->basic_pay) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">RATA</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="rata" class="form-control" step="0.01" 
                                   value="{{ old('rata', optional($employee->compensation)->rata) }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Communication Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="comm_allowance" class="form-control" step="0.01" 
                                   value="{{ old('comm_allowance', optional($employee->compensation)->comm_allowance) }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Transportation Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="transpo_allowance" class="form-control" step="0.01" 
                                   value="{{ old('transpo_allowance', optional($employee->compensation)->transpo_allowance) }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Parking/Toll Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="parking_toll_allowance" class="form-control" step="0.01" 
                                   value="{{ old('parking_toll_allowance', optional($employee->compensation)->parking_toll_allowance) }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Clothing Allowance</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="clothing_allowance" class="form-control" step="0.01" 
                                   value="{{ old('clothing_allowance', optional($employee->compensation)->clothing_allowance) }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Package</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" name="total_package" class="form-control" step="0.01" 
                                   value="{{ old('total_package', optional($employee->compensation)->total_package) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Pay Schedule</label>
                        <select name="pay_schedule" class="form-select" required>
                            <option value="">Select Schedule</option>
                            <option value="Monthly" {{ old('pay_schedule', optional($employee->compensation)->pay_schedule) == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="Semi-Monthly" {{ old('pay_schedule', optional($employee->compensation)->pay_schedule) == 'Semi-Monthly' ? 'selected' : '' }}>Semi-Monthly</option>
                            <option value="Weekly" {{ old('pay_schedule', optional($employee->compensation)->pay_schedule) == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="Bank Transfer" {{ old('payment_method', optional($employee->compensation)->payment_method) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Check" {{ old('payment_method', optional($employee->compensation)->payment_method) == 'Check' ? 'selected' : '' }}>Check</option>
                            <option value="Cash" {{ old('payment_method', optional($employee->compensation)->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Modules
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Compensation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateTotal = () => {
        const basicPay = parseFloat(document.querySelector('[name="basic_pay"]').value) || 0;
        const rata = parseFloat(document.querySelector('[name="rata"]').value) || 0;
        const commAllowance = parseFloat(document.querySelector('[name="comm_allowance"]').value) || 0;
        const transpoAllowance = parseFloat(document.querySelector('[name="transpo_allowance"]').value) || 0;
        const parkingToll = parseFloat(document.querySelector('[name="parking_toll_allowance"]').value) || 0;
        const clothingAllowance = parseFloat(document.querySelector('[name="clothing_allowance"]').value) || 0;

        const total = basicPay + rata + commAllowance + transpoAllowance + parkingToll + clothingAllowance;
        document.querySelector('[name="total_package"]').value = total.toFixed(2);
    };

    // Calculate total when any compensation field changes
    document.querySelectorAll('[name="basic_pay"], [name="rata"], [name="comm_allowance"], [name="transpo_allowance"], [name="parking_toll_allowance"], [name="clothing_allowance"]').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endpush
@endsection