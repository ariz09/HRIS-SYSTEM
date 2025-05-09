<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Education Background</h5>
            </div>
            <div class="card-body">
                <div id="education-container">
                    <!-- Dynamic education fields will be added here -->
                    <div class="education-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Course Taken</label>
                                    <input type="text" name="educations[0][course_taken]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">School</label>
                                    <input type="text" name="educations[0][school_name]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Year Completed</label>
                                    <select name="educations[0][inclusive_dates]" class="form-select" required>
                                        <option value="">Select Year</option>
                                        @foreach(range(1950, date('Y') + 5) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-info mt-3" id="add-education">
                    <i class="fas fa-plus"></i> Add Education
                </button>
            </div>
        </div>
    </div>
</div>