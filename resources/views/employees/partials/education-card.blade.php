<div class="education-card col-md-6">
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2" style="font-size: 0.875rem;">
            <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                Education Record #{{ $index + 1 }}
            </h6>
            <button type="button" class="btn btn-sm btn-light delete-education-btn text-danger rounded-circle" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $education->id ?? '' }}">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                    <input type="text" 
                           name="educations[{{ $index }}][school_name]" 
                           class="form-control uppercase" 
                           value="{{ old("educations.$index.school_name", $education->school_name ?? '') }}" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Course Taken <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                    <input type="text" 
                           name="educations[{{ $index }}][course_taken]" 
                           class="form-control uppercase" 
                           value="{{ old("educations.$index.course_taken", $education->course_taken ?? '') }}" 
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Year Finished <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="number" 
                           name="educations[{{ $index }}][year_finished]" 
                           class="form-control" 
                           value="{{ old("educations.$index.year_finished", $education->year_finished ?? '') }}" 
                           required
                           min="1900"
                           max="{{ date('Y') + 5 }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                    <select name="educations[{{ $index }}][status]" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="undergraduate" {{ (old("educations.$index.status", $education->status ?? '') == 'undergraduate') ? 'selected' : '' }}>
                            UNDERGRADUATE
                        </option>
                        <option value="graduate" {{ (old("educations.$index.status", $education->status ?? '') == 'graduate') ? 'selected' : '' }}>
                            GRADUATE
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>