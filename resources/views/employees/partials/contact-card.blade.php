<div class="contact-card col-md-6">
    <div class="card mb-3 shadow-sm border-0">
         <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
            <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                <i class="fas fa-user-circle me-1"></i>
                Contact #{{ $index + 1 }}
            </h6>
            <button type="button" class="btn btn-sm btn-light delete-contact-btn text-danger rounded-circle p-1" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body">
         <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ old("contacts.$index.id", $contact->id ?? '') }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="contacts[{{ $index }}][fullname]" class="form-control uppercase" value="{{ old("contacts.$index.fullname", $contact->fullname ?? '') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                    <select name="contacts[{{ $index }}][relationship]" class="form-select" required>
                        <option value="">Select Relationship</option>
                        @foreach($relationshipOptions as $value => $label)
                            <option value="{{ $value }}" {{ old("contacts.$index.relationship", $contact->relationship ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" 
                        name="contacts[{{ $index }}][contact_number]" 
                        class="form-control" 
                        value="{{ old("contacts.$index.contact_number", $contact->contact_number ?? '') }}" 
                        required
                        pattern="^(09|\+639)\d{9}$"
                        title="Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)"
                        oninput="formatPhoneNumber(this)">
                </div>
                <div class="invalid-feedback">
                    Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <textarea name="contacts[{{ $index }}][address]" class="form-control uppercase" rows="2">{{ old("contacts.$index.address", $contact->address ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
