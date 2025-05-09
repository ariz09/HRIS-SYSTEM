<div class="row">
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label required">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
            id="email" name="email"
            value="{{ old('email', $employee->email ?? '') }}" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="phone_number" class="form-label required">Phone Number</label>
        <input type="tel" class="form-control" id="phone_number" name="phone_number"
            value="{{ old('phone_number', $employee->phone_number ?? '') }}" required>
    </div>

    <div class="col-12 mb-3">
        <label for="address" class="form-label required">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $employee->address ?? '') }}</textarea>
    </div>
</div>