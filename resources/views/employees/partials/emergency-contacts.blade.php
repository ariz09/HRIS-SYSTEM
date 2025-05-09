<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Emergency Contacts (Minimum 2 required)</h5>
            </div>
            <div class="card-body">
                <div id="emergency-container">
                    <!-- First required emergency contact -->
                    <div class="emergency-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Full Name</label>
                                    <input type="text" name="emergency_contacts[0][name]" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Relationship</label>
                                    <select name="emergency_contacts[0][relationship]" class="form-select" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">Sibling</option>
                                    </select>
                                    <div class="invalid-feedback">Please provide relationship</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Contact Number</label>
                                    <input type="text" name="emergency_contacts[0][phone]" class="form-control" required>
                                    <div class="invalid-feedback">Please provide a contact number</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="emergency_contacts[0][address]" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second required emergency contact -->
                    <div class="emergency-item card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Full Name</label>
                                    <input type="text" name="emergency_contacts[1][name]" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Relationship</label>
                                   <select name="emergency_contacts[1][relationship]" class="form-select" required>
                                        <option value="">Select Relationship</option>
                                        <option value="Spouse">Spouse</option>
                                        <option value="Child">Child</option>
                                        <option value="Parent">Parent</option>
                                        <option value="Sibling">Sibling</option>
                                    </select>
                                    <div class="invalid-feedback">Please provide relationship</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Contact Number</label>
                                    <input type="text" name="emergency_contacts[1][phone]" class="form-control" required>
                                    <div class="invalid-feedback">Please provide a contact number</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="emergency_contacts[1][address]" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-info mt-3" id="add-emergency-contact">
                    <i class="fas fa-plus"></i> Add Additional Emergency Contact
                </button>
            </div>
        </div>
    </div>]
</div>