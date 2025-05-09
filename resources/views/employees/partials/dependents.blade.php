<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Dependents</h5>
            </div>
            <div class="card-body">
                <div id="dependents-container">
                    <!-- Dynamic dependent fields will be added here -->
                    <div class="dependent-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Full Name</label>
                                    <input type="text" name="dependents[0][name]" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Relationship</label>
                                    <select name="dependents[0][relationship]" class="form-select" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">Sibling</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Birthdate</label>
                                    <input type="date" name="dependents[0][birthdate]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-info mt-3" id="add-dependent">
                    <i class="fas fa-plus"></i> Add Dependent
                </button>
            </div>
        </div>
    </div>
</div>