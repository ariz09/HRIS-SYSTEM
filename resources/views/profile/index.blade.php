@extends('layouts.app')

@section('content')
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
        <!-- Personal Information Card -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                </div>
                <div class="card-body">
                    @if($personalInfo)
                        <div class="row">
                            <div class="col-md-4 fw-bold">Name:</div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" 
                                       value="{{strtoupper($personalInfo->preferred_name ?? 
                                                $personalInfo->first_name . ' ' .
                                                $personalInfo->middle_name . ' ' .
                                                $personalInfo->last_name . ' ' .
                                                $personalInfo->name_suffix) }}
                                    "disabled>
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
                                       value="{{ $personalInfo->phone_number ? ucfirst($personalInfo->phone_number) : 'Not specified' }}"
                                       disabled>
                            </div>
                            <div class="col-md-4 fw-bold mt-2">Email:</div>
                            <div class="col-md-8 mt-2">
                                <input type="text" class="form-control" 
                                       value="{{ $personalInfo->email ? $personalInfo->email : 'Not specified' }}"
                                       disabled>
                            </div>
                            
                            <div class="col-md-4 fw-bold mt-2">Address:</div>
                            <div class="col-md-8 mt-2">
                                <textarea  class="form-control" disabled>{{$personalInfo->address ? $personalInfo->address : 'Not specified' }}</textarea>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">No personal information available</div>
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
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-child me-2"></i>Dependents</h6>
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
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Emergency Contacts</h6>
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
</div>
@endsection