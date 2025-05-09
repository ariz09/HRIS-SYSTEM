@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active">View Employee</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-user me-1"></i> Employee Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Personal Information</h5>
                    <table class="table">
                        <tr>
                            <th>Employee Number:</th>
                            <td>{{ $employee->employee_number }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($employee->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Birthday:</th>
                            <td>{{ $employee->birthday ? date('F d, Y', strtotime($employee->birthday)) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Civil Status:</th>
                            <td>{{ ucfirst($employee->civil_status) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Employment Information</h5>
                    <table class="table">
                        <tr>
                            <th>Agency:</th>
                            <td>{{ $employee->agency->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Position:</th>
                            <td>{{ $employee->position->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CDM Level:</th>
                            <td>{{ $employee->cdmLevel->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Employment Status:</th>
                            <td>{{ $employee->employmentStatus->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Hiring Date:</th>
                            <td>{{ $employee->hiring_date ? date('F d, Y', strtotime($employee->hiring_date)) : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Contact Information</h5>
                    <table class="table">
                        <tr>
                            <th>Email:</th>
                            <td>{{ $employee->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $employee->phone_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $employee->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Government IDs</h5>
                    <table class="table">
                        <tr>
                            <th>SSS Number:</th>
                            <td>{{ $employee->sss_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>PhilHealth Number:</th>
                            <td>{{ $employee->philhealth_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Pag-Ibig Number:</th>
                            <td>{{ $employee->pag_ibig_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>TIN:</th>
                            <td>{{ $employee->tin ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Compensation</h5>
                    <table class="table">
                        <tr>
                            <th>Basic Pay:</th>
                            <td>{{ $employee->basic_pay ? number_format($employee->basic_pay, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>RATA:</th>
                            <td>{{ $employee->rata ? number_format($employee->rata, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Communication Allowance:</th>
                            <td>{{ $employee->comm_allowance ? number_format($employee->comm_allowance, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Transportation Allowance:</th>
                            <td>{{ $employee->transpo_allowance ? number_format($employee->transpo_allowance, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Parking/Toll Allowance:</th>
                            <td>{{ $employee->parking_toll_allowance ? number_format($employee->parking_toll_allowance, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Clothing Allowance:</th>
                            <td>{{ $employee->clothing_allowance ? number_format($employee->clothing_allowance, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Total Package:</th>
                            <td>{{ $employee->total_package ? number_format($employee->total_package, 2) : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Bank Information</h5>
                    <table class="table">
                        <tr>
                            <th>Bank:</th>
                            <td>{{ $employee->bank ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ATM Account Number:</th>
                            <td>{{ $employee->atm_account_number ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($employee->dependents->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Dependents</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Relationship</th>
                                <th>Birthdate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->dependents as $dependent)
                            <tr>
                                <td>{{ $dependent->name }}</td>
                                <td>{{ $dependent->relationship }}</td>
                                <td>{{ $dependent->birthdate ? date('F d, Y', strtotime($dependent->birthdate)) : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($employee->emergencyContacts->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Emergency Contacts</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Relationship</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->emergencyContacts as $contact)
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->relationship }}</td>
                                <td>{{ $contact->phone }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($employee->educations->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Education</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Degree</th>
                                <th>School</th>
                                <th>Year Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->educations as $education)
                            <tr>
                                <td>{{ $education->degree }}</td>
                                <td>{{ $education->school_name }}</td>
                                <td>{{ $education->year_completed }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($employee->employmentHistories->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <h5>Employment History</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Position</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->employmentHistories as $history)
                            <tr>
                                <td>{{ $history->company_name }}</td>
                                <td>{{ $history->position }}</td>
                                <td>{{ $history->start_date ? date('F d, Y', strtotime($history->start_date)) : 'N/A' }}</td>
                                <td>{{ $history->end_date ? date('F d, Y', strtotime($history->end_date)) : 'Present' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Employee
                </a>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
