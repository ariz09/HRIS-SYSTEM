<div class="history-card col-6">
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2"
            style="font-size: 0.875rem;">
            <h6 class="mb-0 text-white" style="font-size: 0.875rem;">
                <i class="fas fa-briefcase me-1"></i>
                Employment History #{{ $index + 1 }}
            </h6>
            <button type="button" class="btn btn-sm btn-light delete-history-btn text-danger rounded-circle p-1"
                style="width: 24px; height: 24px; line-height: 1;">
                <i class="fas fa-times" style="font-size: 0.75rem;"></i>
            </button>
        </div>
        <div class="card-body">
            <input type="hidden" name="histories[{{ $index }}][id]"
                value="{{ old("histories.$index.id", $history->id ?? '') }}">

            <div class="row">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <input type="text" name="histories[{{ $index }}][job_title]" class="form-control uppercase"
                            value="{{ old("histories.$index.job_title", $history->job_title ?? '') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" name="histories[{{ $index }}][company_name]" class="form-control uppercase"
                            value="{{ old("histories.$index.company_name", $history->company_name ?? '') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Company Address <span class="text-danger">*</span></label>
                    <textarea name="histories[{{ $index }}][company_address]" class="form-control uppercase" rows="2" required>{{ old("histories.$index.company_address", $history->company_address ?? '') }}</textarea>
                </div>


            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="histories[{{ $index }}][start_date]" class="form-control"
                            value="{{ old("histories.$index.start_date", optional($history->start_date ?? null)->format('Y-m-d')) }}"
                            required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">End Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="histories[{{ $index }}][end_date]" class="form-control"
                            value="{{ old("histories.$index.end_date", optional($history->end_date ?? null)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>