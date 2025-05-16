<div class="dependent-card col-md-6">
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
            <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                Dependent #{{ $index + 1 }}
            </h6>
            <button type="button" class="btn btn-sm btn-light delete-dependent-btn text-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="dependents[{{ $index }}][id]" value="{{ $dependent->id ?? '' }}">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" 
                           name="dependents[{{ $index }}][full_name]" 
                           class="form-control uppercase" 
                           value="{{ old("dependents.$index.full_name", $dependent->full_name ?? '') }}" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Relationship <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                    <select name="dependents[{{ $index }}][dependent_type]" class="form-select" required>
                        <option value="">Select Relationship</option>
                        @foreach($dependentTypeOptions as $value => $label)
                            <option value="{{ $value }}" 
                                @selected(old("dependents.$index.dependent_type", $dependent->dependent_type ?? '') == $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Birthdate <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" 
                           name="dependents[{{ $index }}][birthdate]" 
                           class="form-control" 
                           value="{{ old("dependents.$index.birthdate", isset($dependent->birthdate) ? (is_string($dependent->birthdate) ? $dependent->birthdate : $dependent->birthdate->format('Y-m-d')) : '') }}" 
                           required
                           max="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contact Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" 
                           name="dependents[{{ $index }}][contact_number]" 
                           class="form-control" 
                           value="{{ old("dependents.$index.contact_number", $dependent->contact_number ?? '') }}"
                           pattern="^(09|\+639)\d{9}$"
                           title="Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)"
                           oninput="formatPhoneNumber(this)">
                </div>
                <div class="invalid-feedback">
                    Please enter a valid Philippine mobile number (e.g., 09171234567 or +639171234567)
                </div>
            </div>
        </div>
    </div>
</div>