@extends('layouts.app')
<style>/* Profile Picture Styles */
    .profile-picture-container {
        width: 150px;
        height: 150px;
        border-radius: 5px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin: 0 auto;
    }
    
    .profile-picture {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    
    @media (max-width: 768px) {
        .profile-picture-container {
            width: 120px;
            height: 120px;
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-picture-container {
            width: 150px;
            height: 150px;
        }
    }
    </style>
@section('content')
<x-success-alert :message="session('success')" />
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Profile</h4>
        @if(auth()->user()->is_active)
        {{--     <a href="{{ route('profile.edit') }}" class="btn btn-danger">
                <i class="fas fa-edit"></i> Edit Profile
            </a> --}}
        @endif
    </div>
    
    <div class="row g-4">
        <!-- Profile Picture Upload & Preview Card -->
            <div class="col-md-6 mb-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-danger text-white fw-bold">
                        <i class="fas fa-camera me-1"></i> Profile Picture
                    </div>
                    <div class="card-body">
                        <form id="profilePictureForm" method="POST" action="{{ route('profile.picture.upload') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Current Profile Picture Column -->
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <div class="profile-picture-container mx-auto mb-2">
                                            @if($personalInfo && $personalInfo->profile_picture)
                                                <img id="currentProfilePic"
                                                    src="{{ asset('storage/' . $personalInfo->profile_picture) }}"
                                                    alt="Current Profile Picture" class="img-thumbnail profile-picture">
                                            @else
                                                <img id="currentProfilePic" src="{{ asset('images/default-preview.png') }}"
                                                    alt="Default Profile Picture" class="img-thumbnail profile-picture">
                                            @endif
                                        </div>
                                        <div class="text-muted small mb-3">Current Profile</div>
                                    </div>
                                </div>

                                <!-- Preview Column -->
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="profile-picture-container mx-auto mb-2">
                                            <img id="imagePreview" src="{{ asset('images/default-preview.png') }}"
                                                alt="Image Preview" class="img-thumbnail profile-picture">
                                        </div>
                                        <div class="text-muted small mb-3">New Preview</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Controls -->
                            <div class="mt-3">
                                <label for="profile_picture" class="form-label">Upload New Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture"
                                    accept="image/*" onchange="previewImage(this)">
                                <div class="form-text">JPG, PNG, or GIF (Max 2MB)</div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-success btn-sm mb-1 w-100" id="uploadBtn">
                                        <i class="fas fa-upload me-1"></i> Upload
                                    </button>
                                    
                                    @if($personalInfo && $personalInfo->profile_picture)
                                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#removeProfilePictureModal">
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


<!-- Personal Information Card -->
<div class="col-md-6">
    <div class="card h-100 shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
            <a href="{{ route('profile.personal-info.edit') }}" class="btn btn-sm btn-light">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
        <div class="card-body">
            @if($personalInfo)
                <div class="row">
                    <div class="col-md-4 fw-bold">Name:</div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" 
                            value="{{ strtoupper($personalInfo->preferred_name ?? $personalInfo->first_name . ' ' . $personalInfo->middle_name . ' ' . $personalInfo->last_name . ' ' . $personalInfo->name_suffix) }}"
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Date of Birth:</div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" 
                            value="{{ $personalInfo->birthday ? \Carbon\Carbon::parse($personalInfo->birthday)->format('m/d/Y') : 'Not specified' }}" 
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Gender:</div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" 
                            value="{{ $personalInfo->gender ? ucfirst($personalInfo->gender) : 'Not specified' }}"
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Civil Status:</div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" 
                            value="{{ $personalInfo->civil_status ? ucfirst($personalInfo->civil_status) : 'Not specified' }}"
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Contact:</div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" 
                            value="{{ $personalInfo->phone_number ?? 'Not specified' }}"
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Email:</div>
                    <div class="col-md-8 mt-2">
                        <input type="text" class="form-control" 
                            value="{{ $personalInfo->email ?? 'Not specified' }}"
                            disabled>
                    </div>

                    <div class="col-md-4 fw-bold mt-2">Address:</div>
                    <div class="col-md-8 mt-2">
                        <textarea class="form-control" disabled>{{ $personalInfo->address ?? 'Not specified' }}</textarea>
                    </div>
                </div>
                
            @else
                <div class="alert alert-warning mt-3">No personal information available</div>
            @endif
        </div>
    </div>
</div>

       

        <!-- Employment Information Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Employment Information</h6>
                </div>
                <div class="card-body">
                    @if($employmentInfo)
                        <div class="row">
                            <div class="col-md-4 fw-bold">Employee ID:</div>
                            <div class="col-md-8">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->employee_number }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Position:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->position->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Department:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->department->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Agency:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->agency->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">CDM Level:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->cdmLevel->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Employment Type:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->employmentType->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Employment Status:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->employmentStatus->name ?? 'Not specified' }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Hire Date:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ $employmentInfo->hiring_date ? \Carbon\Carbon::parse($employmentInfo->hiring_date)->format('m/d/Y') : 'Not specified' }}"
                                    disabled>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">No employment information available</div>
                    @endif
                </div>
            </div>
        </div>

       <!-- Compensation Package Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Compensation</h6>
                </div>
                <div class="card-body">
                    @if($compensation)
                        @php
                            $totalPackage = $compensation->basic_pay +
                                            $compensation->rata +
                                            $compensation->comm_allowance +
                                            $compensation->transpo_allowance +
                                            $compensation->clothing_allowance +
                                            $compensation->parking_toll_allowance;
                        @endphp
                        <div class="row">
                            <div class="col-md-4 fw-bold">Basic Pay:</div>
                            <div class="col-md-8">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->basic_pay, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">RATA:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->rata, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Comm Allowance:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->comm_allowance, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Transpo Allowance:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->transpo_allowance, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Clothing Allowance:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->clothing_allowance, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2">Parking/Toll Allowance:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control"
                                    value="{{ number_format($compensation->parking_toll_allowance, 2) }}"
                                    disabled>
                            </div>

                            <div class="col-md-4 fw-bold mt-2 text-danger">Total Package:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control fw-bold text-danger"
                                    value="{{ number_format($totalPackage, 2) }}"
                                    disabled>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">No compensation information available</div>
                    @endif
                </div>
            </div>
        </div>


        <!-- Dependents Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-child me-2"></i>Dependents</h6>
                    <a href="{{ route('profile.dependents.edit') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    @if($dependents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Birthdate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dependents as $dependent)
                                        <tr>
                                            <td>{{ $dependent->full_name }}</td>
                                            <td>{{ ucfirst($dependent->dependent_type) }}</td>
                                            <td>{{ $dependent->birthdate ? \Carbon\Carbon::parse($dependent->birthdate)->format('m/d/Y') : 'Not specified' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No dependents listed</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Emergency Contacts Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Emergency Contacts</h6>
                    <a href="{{ route('profile.emergency-contacts.edit') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    @if($emergencyContacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($emergencyContacts as $contact)
                                        <tr>
                                            <td>{{ $contact->fullname }}</td>
                                            <td>{{ ucfirst($contact->relationship) }}</td>
                                            <td>{{ $contact->contact_number ?? 'N/A'}}</td>
                                            <td>{{ $contact->address ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No emergency contacts listed</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Education Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Education</h6>
                </div>
                <div class="card-body">
                    @if($education->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Institution</th>
                                        <th>Degree</th>
                                        <th>Year Graduated</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($education as $edu)
                                        <tr>
                                            <td>{{ $edu->school_name }}</td>
                                            <td>{{ $edu->course_taken }}</td>
                                            <td>{{ $edu->year_finished }}</td>
                                            <td>{{ $edu->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No education history listed</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Employment History Card -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Employment History</h6>
                </div>
                <div class="card-body">
                    @if($employmentHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Company</th>
                                        <th>Job Title</th>
                                        <th>Duration</th>
                                        <th>Company Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employmentHistory as $history)
                                        <tr>
                                            <td>{{ $history->company_name }}</td>
                                            <td>{{ $history->job_title }}</td>
                                            <td>
                                                {{ $history->start_date->format('m/d/Y') }} - 
                                                {{ $history->end_date ? $history->end_date->format('m/d/Y') : 'Present' }}
                                            </td>
                                            <td>{{ Str::limit($history->company_address, 50) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No employment history listed</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Profile Picture Modal -->
<div class="modal fade" id="removeProfilePictureModal" tabindex="-1" aria-labelledby="removeProfilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="removeProfilePictureModalLabel">Remove Profile Picture</h5>
                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="width: 24px; height: 24px; line-height: 1;">
                    <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                </button>
            </div>
            <form id="removeProfilePictureForm" method="POST" action="{{ route('profile.picture.remove') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to remove your profile picture?</p>
                    <p class="text-danger fw-bold mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>


</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            uploadBtn.disabled = false;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Disable upload button initially
document.getElementById('uploadBtn').disabled = true;
</script>
@endpush