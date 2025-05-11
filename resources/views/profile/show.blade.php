@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('status') === 'personal-info-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message', 'Personal information updated successfully.') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">Profile Information</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Personal Information</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">
                        Edit Information
                    </button>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">First Name:</div>
                        <div class="col-md-6">{{ $personalInfo->first_name ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Middle Name:</div>
                        <div class="col-md-6">{{ $personalInfo->middle_name ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Last Name:</div>
                        <div class="col-md-6">{{ $personalInfo->last_name ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Name Suffix:</div>
                        <div class="col-md-6">{{ $personalInfo->name_suffix ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Preferred Name:</div>
                        <div class="col-md-6">{{ $personalInfo->preferred_name ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Gender:</div>
                        <div class="col-md-6">{{ $personalInfo->gender ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Birthday:</div>
                        <div class="col-md-6">{{ $personalInfo?->birthday ? $personalInfo->birthday->format('F d, Y') : 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Email:</div>
                        <div class="col-md-6">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Phone Number:</div>
                        <div class="col-md-6">{{ $personalInfo->phone_number ?? 'Not set' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Civil Status:</div>
                        <div class="col-md-6">{{ $personalInfo->civil_status ?? 'Not set' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Personal Information Modal -->
<div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editPersonalInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonalInfoModalLabel">Edit Personal Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('profile.personal.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name" name="first_name" value="{{ old('first_name', $personalInfo?->first_name) }}">
                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="middle_name" class="col-md-4 col-form-label text-md-end">Middle Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                   id="middle_name" name="middle_name" value="{{ old('middle_name', $personalInfo?->middle_name) }}">
                            @error('middle_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name" name="last_name" value="{{ old('last_name', $personalInfo?->last_name) }}">
                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name_suffix" class="col-md-4 col-form-label text-md-end">Name Suffix</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name_suffix') is-invalid @enderror"
                                   id="name_suffix" name="name_suffix" value="{{ old('name_suffix', $personalInfo?->name_suffix) }}">
                            @error('name_suffix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="preferred_name" class="col-md-4 col-form-label text-md-end">Preferred Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('preferred_name') is-invalid @enderror"
                                   id="preferred_name" name="preferred_name" value="{{ old('preferred_name', $personalInfo?->preferred_name) }}">
                            @error('preferred_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="gender" class="col-md-4 col-form-label text-md-end">Gender</label>
                        <div class="col-md-6">
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $personalInfo?->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $personalInfo?->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $personalInfo?->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="birthday" class="col-md-4 col-form-label text-md-end">Birthday</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                   id="birthday" name="birthday" value="{{ old('birthday', $personalInfo?->birthday ? $personalInfo->birthday->format('Y-m-d') : '') }}">
                            @error('birthday')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
                        <div class="col-md-6">
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $personalInfo?->phone_number) }}">
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="civil_status" class="col-md-4 col-form-label text-md-end">Civil Status</label>
                        <div class="col-md-6">
                            <select class="form-select @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status">
                                <option value="">Select Civil Status</option>
                                <option value="Single" {{ old('civil_status', $personalInfo?->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('civil_status', $personalInfo?->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('civil_status', $personalInfo?->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Separated" {{ old('civil_status', $personalInfo?->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                <option value="Divorced" {{ old('civil_status', $personalInfo?->civil_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                            @error('civil_status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
