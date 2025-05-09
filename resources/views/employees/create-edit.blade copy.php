@extends('layouts.app')

@section('content')
<x-success-alert :message="session('success')" />
<x-error-alert :errors="$errors" />

<div class="container-fluid px-4">
    <h1 class="mt-4">Employee Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">{{ isset($employee) ? 'Edit' : 'Create' }} Employee</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-user me-1"></i> Employee Details
        </div>
        <div class="card-body">
            <form action="{{ isset($employee) ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
                @csrf
                @if(isset($employee))
                    @method('PUT')
                @endif

                <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <span class="nav-link active" id="personal-tab" role="tab">Personal Info</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="government-tab" role="tab">Government IDs</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="employment-tab" role="tab">Employment</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="contact-tab" role="tab">Contact</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="compensation-tab" role="tab">Compensation</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="education-tab" role="tab">Education</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="dependents-tab" role="tab">Dependents</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="emergency-tab" role="tab">Emergency</span>
                    </li>
                    <li class="nav-item" role="presentation">
                        <span class="nav-link" id="history-tab" role="tab">History</span>
                    </li>
                </ul>

                <div class="tab-content" id="employeeTabContent">
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        @include('employees.partials.personal-info', [
                            'employee' => $employee ?? null,
                            'formattedEmployeeNumber' => $formattedEmployeeNumber ?? null
                        ])
                    </div>

                    <!-- Government IDs Tab -->
                    <div class="tab-pane fade" id="government" role="tabpanel" aria-labelledby="government-tab">
                        @include('employees.partials.government-ids', [
                            'employee' => $employee ?? null
                        ])
                    </div>

                    <!-- Employment Info Tab -->
                    <div class="tab-pane fade" id="employment" role="tabpanel" aria-labelledby="employment-tab">
                        @include('employees.partials.employment', [
                            'employee' => $employee ?? null,
                            'agencies' => $agencies,
                            'departments' => $departments,
                            'cdmLevels' => $cdmLevels,
                            'positions' => $positions,
                            'employmentStatuses' => $employmentStatuses
                        ])
                    </div>

                    <!-- Contact Info Tab -->
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        @include('employees.partials.contact', [
                            'employee' => $employee ?? null
                        ])
                    </div>

                    <!-- Compensation Tab -->
                    <div class="tab-pane fade" id="compensation" role="tabpanel" aria-labelledby="compensation-tab">
                        @include('employees.partials.compensation', [
                            'employee' => $employee ?? null
                        ])
                    </div>

                    <!-- Education Tab -->
                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                        @include('employees.partials.education', [
                            'employee' => $employee ?? null,
                            'formOptions' => $formOptions
                        ])
                    </div>

                    <!-- Dependents Tab -->
                    <div class="tab-pane fade" id="dependents" role="tabpanel" aria-labelledby="dependents-tab">
                        @include('employees.partials.dependents', [
                            'employee' => $employee ?? null,
                            'formOptions' => $formOptions
                        ])
                    </div>

                    <!-- Emergency Contacts Tab -->
                    <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                        @include('employees.partials.emergency-contacts', [
                            'employee' => $employee ?? null,
                            'formOptions' => $formOptions
                        ])
                    </div>

                    <!-- Employment History Tab -->
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        @include('employees.partials.employment-history', [
                            'employee' => $employee ?? null
                        ])
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevTabBtn" disabled>
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>

                        <div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Employee
                            </button>
                        </div>

                        <button type="button" class="btn btn-primary" id="nextTabBtn">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #dc3545 !important;
        color: #495057 !important;
        font-weight: 500;
    }

    .nav-tabs .nav-link:not(.active) {
        color: #6c757d;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }

    .dynamic-field-container .card {
        position: relative;
    }

    .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.7;
    }

    .remove-item:hover {
        opacity: 1;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {

    const tabs = [
        'personal', 'government', 'employment', 'contact', 
        'compensation', 'education', 'dependents', 'emergency', 'history'
    ];
    let currentTab = 0;

    function showTab(index) {
        if (index > currentTab && !validateCurrentTab()) return;

        currentTab = index;
        $('.nav-link').removeClass('active');
        $(`#${tabs[index]}-tab`).addClass('active');

        $('.tab-pane').removeClass('show active');
        $(`#${tabs[index]}`).addClass('show active');

        $('#prevTabBtn').prop('disabled', index === 0);
        $('#nextTabBtn').toggle(index < tabs.length - 1);
        $('button[type="submit"]').toggle(index === tabs.length - 1);

        setTimeout(() => {
            const $firstInput = $(`#${tabs[index]} :input:visible:not([readonly]):first`);
            if ($firstInput.length) $firstInput.focus();
        }, 100);
    }

    function validateCurrentTab() {
        return validateTab(tabs[currentTab]);
    }

    function validateTab(tabId) {
        const $tab = $(`#${tabId}`);
        let isValid = true;

        $tab.find('.is-invalid').removeClass('is-invalid');
        $tab.find('.invalid-feedback').hide();

        $tab.find(':input[required]:visible').each(function() {
            const $input = $(this);
            if (!$input.val()?.trim()) {
                $input.addClass('is-invalid');
                let $feedback = $input.next('.invalid-feedback');
                if (!$feedback.length) {
                    $feedback = $('<div class="invalid-feedback">This field is required</div>');
                    $input.after($feedback);
                }
                $feedback.show();
                isValid = false;
            }
        });

        if (!isValid) {
            const $firstError = $tab.find('.is-invalid').first();
            $('html, body').animate({ scrollTop: $firstError.offset().top - 100 }, 500);
            setTimeout(() => $firstError.focus(), 500);
            showErrorNotification('Please fill in all required fields');
        }

        return isValid;
    }

    function showErrorNotification(message) {
        let $notification = $('#form-error-notification');
        if (!$notification.length) {
            $notification = $(`
                <div id="form-error-notification" class="alert alert-danger alert-dismissible fade show" role="alert"
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            $('body').append($notification);
        } else {
            $notification.html(`
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `).show();
        }

        setTimeout(() => $notification.alert('close'), 5000);
    }

    showTab(0);

    $('#nextTabBtn').on('click', () => {
        if (currentTab < tabs.length - 1) showTab(currentTab + 1);
    });

    $('#prevTabBtn').on('click', () => {
        if (currentTab > 0) showTab(currentTab - 1);
    });

    $('.nav-link').on('click', function(e) {
        const tabId = $(this).attr('id').replace('-tab', '');
        const newIndex = tabs.indexOf(tabId);
        if (newIndex > currentTab && !validateCurrentTab()) {
            e.preventDefault();
            return;
        }
        showTab(newIndex);
    });

    function setupDynamicFields(containerSelector, addButtonSelector, templateFunction) {
        let index = $(containerSelector).children().length;

        $(addButtonSelector).on('click', function() {
            $(containerSelector).append(templateFunction(index++));
        });

        $(document).on('click', `${containerSelector} .remove-item`, function() {
            if ($(containerSelector).children().length > 1) {
                $(this).closest('.card').remove();
                reindexFields(containerSelector);
            } else {
                showErrorNotification('At least one item is required.');
            }
        });
    }

    function reindexFields(containerSelector) {
        $(containerSelector).children().each((index, item) => {
            $(item).find('[name]').each(function() {
                const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', name);
            });
        });
    }

    // YEAR OPTIONS FROM BLADE VARIABLE
    const years = JSON.parse("{!! json_encode($formOptions['years'] ?? range(1950, now()->year + 5)) !!}");
    let yearOptions = '<option value="">Select Year</option>';
    years.forEach(year => {
        yearOptions += `<option value="${year}">${year}</option>`;
    });

    // Education
    setupDynamicFields(
        '#education-container',
        '#add-education',
        (index) => `
            <div class="education-item card mb-3">
                <div class="card-body">
                    <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Course Taken</label>
                            <input type="text" name="educations[${index}][course_taken]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">School</label>
                            <input type="text" name="educations[${index}][school_name]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Year Completed</label>
                            <select name="educations[${index}][inclusive_dates]" class="form-select" required>
                                ${yearOptions}
                            </select>
                        </div>
                    </div>
                </div>
            </div>`
    );

    // Dependents
    setupDynamicFields(
        '#dependents-container',
        '#add-dependent',
        (index) => `
            <div class="dependent-item card mb-3">
                <div class="card-body">
                    <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="dependents[${index}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Relationship</label>
                            <select name="dependents[${index}][relationship]" class="form-select" required>
                                <option value="">Select Relationship</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Birthdate</label>
                            <input type="date" name="dependents[${index}][birthdate]" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>`
    );

    // Emergency Contacts
    setupDynamicFields(
        '#emergency-container',
        '#add-emergency-contact',
        (index) => `
            <div class="emergency-item card mb-3">
                <div class="card-body">
                    <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Full Name</label>
                            <input type="text" name="emergency_contacts[${index}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Relationship</label>
                             <select name="emergency_contacts[${index}][relationship]" class="form-select" required>
                                <option value="">Select Relationship</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                                <option value="Sibling">Sibling</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Contact Number</label>
                            <input type="text" name="emergency_contacts[${index}][phone]" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Address</label>
                            <textarea name="emergency_contacts[${index}][address]" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
            </div>`
    );

    // Employment History
    setupDynamicFields(
        '#history-container',
        '#add-history',
        (index) => `
            <div class="history-item card mb-3">
                <div class="card-body">
                    <button type="button" class="btn-close float-end remove-item" aria-label="Close"></button>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Company Name</label>
                            <input type="text" name="employment_history[${index}][company_name]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Position</label>
                            <input type="text" name="employment_history[${index}][position]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Start Date</label>
                            <input type="date" name="employment_history[${index}][start_date]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="employment_history[${index}][end_date]" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Responsibilities</label>
                            <textarea name="employment_history[${index}][responsibilities]" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>`
    );

    // Form Submission Validation
    $('#employeeForm').on('submit', function(e) {
        let allValid = true;

        for (let i = 0; i < tabs.length; i++) {
            if (!validateTab(tabs[i])) {
                allValid = false;
                showTab(i);
                break;
            }
        }

        const emergencyContacts = $('#emergency-container .emergency-item').length;
        if (emergencyContacts < 2) {
            showErrorNotification('Please provide at least 2 emergency contacts');
            showTab(tabs.indexOf('emergency'));
            allValid = false;
        }

        if (!allValid) {
            e.preventDefault();
            return false;
        }

        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    });

    // 5. Compensation Calculation
    function calculateTotalPackage() {
        const basicPay = parseFloat($('#basic_pay').val()) || 0;
        const rata = parseFloat($('#rata').val()) || 0;
        const commAllowance = parseFloat($('#comm_allowance').val()) || 0;
        const transpoAllowance = parseFloat($('#transpo_allowance').val()) || 0;
        const parkingToll = parseFloat($('#parking_toll_allowance').val()) || 0;
        const clothingAllowance = parseFloat($('#clothing_allowance').val()) || 0;

        const total = basicPay + rata + commAllowance + transpoAllowance + parkingToll + clothingAllowance;
        $('#total_package').val(total.toFixed(2));
    }

    $('#basic_pay, #rata, #comm_allowance, #transpo_allowance, #parking_toll_allowance, #clothing_allowance').on('input', calculateTotalPackage);

    // Initialize calculation
    calculateTotalPackage();
    

});
</script>
@endpush