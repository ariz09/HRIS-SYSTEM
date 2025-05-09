<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Employment History</h5>
            </div>
            <div class="card-body">
                <div id="history-container">
                    <!-- Dynamic employment history fields will be added here -->
                    <div class="history-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Company Name</label>
                                    <input type="text" name="employment_history[0][company_name]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Position</label>
                                    <input type="text" name="employment_history[0][position]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Start Date</label>
                                    <input type="date" name="employment_history[0][start_date]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="employment_history[0][end_date]" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-info mt-3" id="add-history">
                    <i class="fas fa-plus"></i> Add Employment History
                </button>
            </div>
        </div>
    </div>
</div>