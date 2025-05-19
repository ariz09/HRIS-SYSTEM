<div class="contact-card col-md-6">
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
            <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                <i class="fas fa-user-circle me-1"></i>
                Contact #{{ $index + 1 }}
            </h6>
            <button type="button" class="btn btn-sm btn-light delete-contact-btn text-danger rounded-circle p-1"
                style="width: 24px; height: 24px; line-height: 1;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body">
            @if(isset($contact->id))
                <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="contacts[{{ $index }}][fullname]" 
                        class="form-control uppercase @error("contacts.$index.fullname") is-invalid @enderror" 
                        value="{{ old("contacts.$index.fullname", $contact->fullname ?? '') }}" required>
                    @error("contacts.$index.fullname")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                    <select name="contacts[{{ $index }}][relationship]" 
                        class="form-select @error("contacts.$index.relationship") is-invalid @enderror" required>
                        <option value="">Select Relationship</option>
                        @foreach($relationshipOptions as $value => $label)
                            <option value="{{ $value }}"
                                {{ old("contacts.$index.relationship", $contact->relationship ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error("contacts.$index.relationship")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" 
                        name="contacts[{{ $index }}][contact_number]" 
                        class="form-control @error("contacts.$index.contact_number") is-invalid @enderror" 
                        value="{{ old("contacts.$index.contact_number", $contact->contact_number ?? '') }}" 
                        required
                        pattern="^(09|\+639)\d{9}$"
                        title="Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)"
                        oninput="formatPhoneNumber(this)">
                    @error("contacts.$index.contact_number")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">
                            Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)
                        </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <textarea name="contacts[{{ $index }}][address]" 
                        class="form-control uppercase @error("contacts.$index.address") is-invalid @enderror" 
                        rows="2">{{ old("contacts.$index.address", $contact->address ?? '') }}</textarea>
                    @error("contacts.$index.address")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
