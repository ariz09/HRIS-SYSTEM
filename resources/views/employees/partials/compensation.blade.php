<div class="row">
    <div class="col-md-6 mb-3">
        <label for="basic_pay" class="form-label required">Basic Pay</label>
        <input type="number" step="0.01" class="form-control @error('basic_pay') is-invalid @enderror"
            id="basic_pay" name="basic_pay"
            value="{{ old('basic_pay', $employee->basic_pay ?? '') }}" required>
        @error('basic_pay')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="rata" class="form-label">RATA</label>
        <input type="number" step="0.01" class="form-control"
            id="rata" name="rata"
            value="{{ old('rata', $employee->rata ?? '') }}">
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="comm_allowance" class="form-label">Communication Allowance</label>
        <input type="number" step="0.01" class="form-control"
            id="comm_allowance" name="comm_allowance"
            value="{{ old('comm_allowance', $employee->comm_allowance ?? '') }}">
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="transpo_allowance" class="form-label">Transportation Allowance</label>
        <input type="number" step="0.01" class="form-control"
            id="transpo_allowance" name="transpo_allowance"
            value="{{ old('transpo_allowance', $employee->transpo_allowance ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label for="parking_toll_allowance" class="form-label">Parking/Toll Allowance</label>
        <input type="number" step="0.01" class="form-control"
            id="parking_toll_allowance" name="parking_toll_allowance"
            value="{{ old('parking_toll_allowance', $employee->parking_toll_allowance ?? '') }}">
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="clothing_allowance" class="form-label">Clothing Allowance</label>
        <input type="number" step="0.01" class="form-control"
            id="clothing_allowance" name="clothing_allowance"
            value="{{ old('clothing_allowance', $employee->clothing_allowance ?? '') }}">
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="total_package" class="form-label">Total Package</label>
        <input type="number" step="0.01" class="form-control"
            id="total_package" name="total_package"
            value="{{ old('total_package', $employee->total_package ?? '') }}" readonly>
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="atm_account_number" class="form-label required">ATM Account Number</label>
        <input type="text" class="form-control"
            id="atm_account_number" name="atm_account_number"
            value="{{ old('atm_account_number', $employee->atm_account_number ?? '') }}" required>
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="bank" class="form-label required">Bank</label>
        <input type="text" class="form-control"
            id="bank" name="bank"
            value="{{ old('bank', $employee->bank ?? '') }}" required>
    </div>
</div>