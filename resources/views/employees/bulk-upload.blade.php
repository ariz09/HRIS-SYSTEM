@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Bulk Employee Upload</h1>
    <p class="mb-4">Upload multiple employee records via CSV file.</p>

    <!-- Upload Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upload CSV File</h6>
        </div>
        <div class="card-body">
           <form method="POST" action="{{ route('employees.bulk-upload.process') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">CSV File</label>
                    <input 
                        type="file" 
                        class="form-control @error('file') is-invalid @enderror" 
                        id="file" 
                        name="file" 
                        accept=".csv" 
                        required
                    >
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        The CSV file must contain the following columns:<br>
                        Agency, Department, CDM Level, Gender, Employment Type, Employee Number, Last Name, First Name, Middle Name, Name Suffix, Alias, Position, Hiring Date, Last Day, Employment Status, Tenure, Basic Pay, RATA, Comm Allowance, Transpo Allowance, Parking/Toll Allowance, Clothing Allowance, Total Package, ATM Account Number, Birthday, SSS Number, Philhealth Number, Pag-Ibig Number, TIN
                    </small>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="create_user_accounts" name="create_user_accounts" value="1">
                    <label class="form-check-label" for="create_user_accounts">Create user accounts for employees</label>
                </div>

                <div class="alert alert-info">
                    <strong>Note:</strong> CDM Level, Position, Gender, Employment Type, and Employment Status values will be matched or created automatically.
                </div>

                <button type="submit" class="btn btn-primary">Upload and Process</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <!-- Sample CSV Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sample CSV Format</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach ([
                                'Agency', 'Department', 'CDM Level', 'Gender', 'Employment Type', 'Employee Number',
                                'Last Name', 'First Name', 'Middle Name', 'Name Suffix', 'Alias', 'Position',
                                'Hiring Date', 'Last Day', 'Employment Status', 'Tenure', 'Basic Pay', 'RATA',
                                'Comm Allowance', 'Transpo Allowance', 'Parking/Toll Allowance', 'Clothing Allowance',
                                'Total Package', 'ATM Account Number', 'Birthday', 'SSS Number', 'Philhealth Number',
                                'Pag-Ibig Number', 'TIN'
                            ] as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sample Agency</td>
                            <td>IT Department</td>
                            <td>Level 5</td>
                            <td>Male</td>
                            <td>Regular</td>
                            <td>EMP00123</td>
                            <td>Doe</td>
                            <td>John</td>
                            <td>Middle</td>
                            <td>Jr.</td>
                            <td>jdoe</td>
                            <td>Software Engineer</td>
                            <td>2020-01-15</td>
                            <td></td>
                            <td>Active</td>
                            <td>3-5 years</td>
                            <td>40000</td>
                            <td>5000</td>
                            <td>3000</td>
                            <td>2000</td>
                            <td>1000</td>
                            <td>1500</td>
                            <td>52500</td>
                            <td>1234567890</td>
                            <td>1985-05-15</td>
                            <td>12-3456789-0</td>
                            <td>12-345678901-2</td>
                            <td>1234-5678-9012</td>
                            <td>123-456-789-000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <a href="#" class="btn btn-success btn-sm" id="downloadSample">
                    <i class="fas fa-download"></i> Download Sample CSV
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('downloadSample').addEventListener('click', function(e) {
        e.preventDefault();

        // Use static sample data if variables aren't available
        const sampleData = {
            department: 'IT Department',
            position: 'Software Engineer',
            cdmLevel: 'Level 5',
            gender: 'Male',
            employmentType: 'Regular',
            employmentStatus: 'Active'
        };

        const headers = [
            'Agency', 'Department', 'CDM Level', 'Gender', 'Employment Type', 
            'Employee Number', 'Last Name', 'First Name', 'Middle Name', 'Name Suffix', 
            'Alias', 'Position', 'Hiring Date', 'Last Day', 'Employment Status', 
            'Tenure', 'Basic Pay', 'RATA', 'Comm Allowance', 'Transpo Allowance', 
            'Parking/Toll Allowance', 'Clothing Allowance', 'Total Package', 
            'ATM Account Number', 'Birthday', 'SSS Number', 'Philhealth Number', 
            'Pag-Ibig Number', 'TIN'
        ];

        const sample = [
            'Sample Agency', 
            sampleData.department,
            sampleData.cdmLevel,
            sampleData.gender,
            sampleData.employmentType,
            'EMP00123', 'Doe', 'John', 'Middle', 'Jr.', 
            'jdoe', sampleData.position, '2020-01-15', '', sampleData.employmentStatus, 
            '3-5 years', '40000', '5000', '3000', '2000', 
            '1000', '1500', '52500', '1234567890', '1985-05-15', 
            '12-3456789-0', '12-345678901-2', '1234-5678-9012', '123-456-789-000'
        ];

        const csv = [headers.join(','), sample.join(',')].join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', 'employee_bulk_upload_sample.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>
@endpush
