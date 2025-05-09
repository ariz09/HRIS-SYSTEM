<div class="row">
    <div class="col-md-6 mb-3">
        <label for="sss_number" class="form-label required">SSS Number</label>
        <input type="text" class="form-control" id="sss_number" name="sss_number"
            value="{{ old('sss_number', $employee->sss_number ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="philhealth_number" class="form-label required">PhilHealth Number</label>
        <input type="text" class="form-control" id="philhealth_number" name="philhealth_number"
            value="{{ old('philhealth_number', $employee->philhealth_number ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="pag_ibig_number" class="form-label required">Pag-Ibig Number</label>
        <input type="text" class="form-control" id="pag_ibig_number" name="pag_ibig_number"
            value="{{ old('pag_ibig_number', $employee->pag_ibig_number ?? '') }} "required>
    </div>
    
    <div class="col-md-6 mb-3">
        <label for="tin" class="form-label required">TIN</label>
        <input type="text" class="form-control" id="tin" name="tin"
            value="{{ old('tin', $employee->tin ?? '') }}" required>
    </div>
</div>