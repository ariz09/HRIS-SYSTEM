@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{ $employee->exists ? 'Edit' : 'Create' }} Employee</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-user me-1"></i> Employee Modules
        </div>
        <div class="card-body">
            @if($employee->exists)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo" class="img-thumbnail" width="100">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4>{{ $employee->full_name }}</h4>
                                <p class="mb-1">Employee #: {{ $employee->employee_number }}</p>
                                <p class="mb-1">Position: {{ $employee->position->name ?? 'N/A' }}</p>
                                <p class="mb-0">Department: {{ $employee->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Personal Info Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->personalInfo ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-4x {{ $employee->personalInfo ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Personal Information</h5>
                            <p class="card-text">Basic details and identification</p>
                            <a href="{{ $employee->exists ? route('employees.personal.edit', $employee) : route('employees.personal.create') }}" 
                               class="btn btn-sm {{ $employee->personalInfo ? 'btn-outline-success' : 'btn-danger' }}">
                                {{ $employee->personalInfo ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->personalInfo)
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Government IDs Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ ($employee->governmentIds && $employee->governmentIds->isNotEmpty()) ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-id-card fa-4x {{ ($employee->governmentIds && $employee->governmentIds->isNotEmpty()) ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Government IDs</h5>
                            <p class="card-text">SSS, TIN, Philhealth, etc.</p>
                            <a href="{{ $employee->exists ? route('employees.government.edit', $employee) : '#' }}" 
                            class="btn btn-sm {{ ($employee->governmentIds && $employee->governmentIds->isNotEmpty()) ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ ($employee->governmentIds && $employee->governmentIds->isNotEmpty()) ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->governmentIds && $employee->governmentIds->isNotEmpty())
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Employment Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->employment ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-briefcase fa-4x {{ $employee->employment ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Employment</h5>
                            <p class="card-text">Position, department, status</p>
                            <a href="{{ $employee->exists ? route('employees.employment.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->employment ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->employment ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->employment)
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->contactInfo ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-address-book fa-4x {{ $employee->contactInfo ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Contact Information</h5>
                            <p class="card-text">Address, phone, email</p>
                            <a href="{{ $employee->exists ? route('employees.contact.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->contactInfo ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->contactInfo ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->contactInfo)
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Compensation Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->compensation ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-money-bill-wave fa-4x {{ $employee->compensation ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Compensation</h5>
                            <p class="card-text">Salary, allowances, benefits</p>
                            <a href="{{ $employee->exists ? route('employees.compensation.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->compensation ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->compensation ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->compensation)
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Education Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->educations->isNotEmpty() ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-graduation-cap fa-4x {{ $employee->educations->isNotEmpty() ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Education</h5>
                            <p class="card-text">Academic background</p>
                            <a href="{{ $employee->exists ? route('employees.education.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->educations->isNotEmpty() ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->educations->isNotEmpty() ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->educations->isNotEmpty())
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dependents Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->dependents->isNotEmpty() ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-users fa-4x {{ $employee->dependents->isNotEmpty() ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Dependents</h5>
                            <p class="card-text">Family members</p>
                            <a href="{{ $employee->exists ? route('employees.dependents.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->dependents->isNotEmpty() ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->dependents->isNotEmpty() ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->dependents->isNotEmpty())
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Emergency Contacts Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->emergencyContacts->isNotEmpty() ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-phone-alt fa-4x {{ $employee->emergencyContacts->isNotEmpty() ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Emergency Contacts</h5>
                            <p class="card-text">Emergency contact persons</p>
                            <a href="{{ $employee->exists ? route('employees.emergency.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->emergencyContacts->isNotEmpty() ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->emergencyContacts->isNotEmpty() ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->emergencyContacts->isNotEmpty())
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Employment History Module -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $employee->employmentHistories->isNotEmpty() ? 'border-success' : '' }}">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-history fa-4x {{ $employee->employmentHistories->isNotEmpty() ? 'text-success' : 'text-secondary' }}"></i>
                            </div>
                            <h5 class="card-title">Employment History</h5>
                            <p class="card-text">Previous work experience</p>
                            <a href="{{ $employee->exists ? route('employees.history.edit', $employee) : '#' }}" 
                               class="btn btn-sm {{ $employee->employmentHistories->isNotEmpty() ? 'btn-outline-success' : 'btn-danger' }} {{ !$employee->exists ? 'disabled' : '' }}">
                                {{ $employee->employmentHistories->isNotEmpty() ? 'Edit' : 'Add' }}
                            </a>
                            @if($employee->employmentHistories->isNotEmpty())
                                <span class="badge bg-success ms-2">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($employee->exists && $employee->isComplete())
                <div class="text-center mt-4">
                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-success">
                        <i class="fas fa-eye"></i> View Complete Employee Profile
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .disabled {
        pointer-events: none;
        opacity: 0.6;
    }
    .module-card {
        min-height: 250px;
    }
    .module-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush