<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employees.bulkUpload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow-sm">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="bulkUploadModalLabel">Upload CSV File</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" required accept=".csv">
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('employees.template.download') }}" class="btn btn-primary btn-sm" target="_blank" download>
                            Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
