<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    DepartmentController,
    LeaveController,
    LeaveTypeController,
    PositionController,
    SearchController,
    AssignLeaveController,
    AgencyController,
    CDMLevelController,
    DashboardController,
    EmploymentTypeController,
    HolidayController,
    RoleController,
    RolePermissionController,
    LeaveApplicationController,
    EmployeeInfoController,
    PersonalInfoController,
    EmployeeController,
    EmployeePersonalInfoController,
    EmployeeEmergencyContactController,
    EmployeeDependentController,
    EmployeeEducationController,
    EmployeeEmploymentHistoryController,
    TimeRecordController,
    File201Controller,
    ExcessTimeController,
    PeriodTypeController,
    CutOffTypeController,
    BulkUploadTemplateController,
    InactiveUserController,
    ProfilePictureController,
    UserPersonalInfoController,
    ProfileDependentController,
    ProfileEmergencyContactController,
    ProfileEducationController,
    ProfileEmploymentHistoryController
};

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Redirect root to login
Route::redirect('/', '/login');
Route::view('/no-access', 'auth.no-access')->name('no.access');

// Guest Routes (Unauthenticated)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated User Routes
Route::middleware(['auth', \App\Http\Middleware\CheckActiveUser::class])->group(function () {

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    });

    // Profile picture routes
    Route::post('/profile/picture/upload', [ProfilePictureController::class, 'upload'])
        ->name('profile.picture.upload');

    Route::delete('/profile/picture/remove', [ProfilePictureController::class, 'destroy'])
        ->name('profile.picture.remove');

    Route::get('/profile/personal-info/edit', [UserPersonalInfoController::class, 'edit'])->name('profile.personal-info.edit');
    Route::put('/profile/personal-info/update', [UserPersonalInfoController::class, 'update'])->name('profile.personal-info.update');
    Route::get('/profile/dependents/edit', [ProfileDependentController::class, 'edit'])->name('profile.dependents.edit');
    Route::put('/profile/dependents/update', [ProfileDependentController::class, 'update'])->name('profile.dependents.update');
    Route::delete('/profile/dependents/{dependent}', [ProfileDependentController::class, 'destroy'])->name('profile.dependents.destroy');
    // For regular users editing their own emergency contacts
    Route::get('/profile/emergency-contacts/edit', [ProfileEmergencyContactController::class, 'edit'])->name('profile.emergency-contacts.edit');
    Route::put('/profile/emergency-contacts/update', [ProfileEmergencyContactController::class, 'update'])->name('profile.emergency-contacts.update');
    Route::delete('/profile/emergency-contacts/{contact}', [ProfileEmergencyContactController::class, 'destroy'])->name('profile.emergency-contacts.destroy');
    Route::get('/profile/educations/edit', [ProfileEducationController::class, 'edit'])->name('profile.educations.edit');
    Route::put('/profile/educations/update', [ProfileEducationController::class, 'update'])->name('profile.educations.update');
    Route::delete('/profile/educations/{education}', [ProfileEducationController::class, 'destroy'])->name('profile.educations.destroy');
    // For regular users editing their own employment history
    Route::get('/profile/employment-history/edit', [ProfileEmploymentHistoryController::class, 'edit'])->name('profile.employment-history.edit');
    Route::put('/profile/employment-history/update', [ProfileEmploymentHistoryController::class, 'update'])->name('profile.employment-history.update');
    Route::delete('/profile/employment-history/{history}', [ProfileEmploymentHistoryController::class, 'destroy'])->name('profile.employment-history.destroy');
    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');
    Route::resource('period_types', PeriodTypeController::class);
    Route::resource('cut_off_types', CutOffTypeController::class);

    Route::get('/inactive-users', [InactiveUserController::class, 'index'])->name('inactive-users.index');
    Route::post('/users/{user}/activate', [InactiveUserController::class, 'activate'])->name('users.activate');
    Route::get('/inactive-users/count', [InactiveUserController::class, 'getInactiveCount'])->name('inactive-users.count');

    // Time Records - accessible by all employees and timekeeper
    Route::prefix('time-records')->middleware(['role:employee|timekeeper|admin'])->group(function () {
        Route::get('/', [TimeRecordController::class, 'index'])->name('time-records.index');
        Route::post('/time-in', [TimeRecordController::class, 'timeIn'])->name('time-in');
        Route::post('/time-out', [TimeRecordController::class, 'timeOut'])->name('time-out');
        Route::post('/time-out/update-status', [TimeRecordController::class, 'updateTimeInStatus'])->name('time-out.update-status');
        Route::get('/my', [TimeRecordController::class, 'myTimeRecords'])->name('time-records.my');
        Route::get('/all', [TimeRecordController::class, 'allTimeRecords'])->middleware(['role:timekeeper|admin'])->name('time-records.all');
    });

    // Main Employee resource route
    Route::resource('employees', EmployeeController::class);


    Route::prefix('employees/{employee}/employment-histories')->group(function () {
        Route::get('edit', [EmployeeEmploymentHistoryController::class, 'edit'])
            ->name('employees.employment-histories.edit');
        Route::put('update', [EmployeeEmploymentHistoryController::class, 'update'])
            ->name('employees.employment-histories.update');
        Route::delete('{history}', [EmployeeEmploymentHistoryController::class, 'destroy'])
            ->name('employees.employment-histories.destroy');
    });

    // Employee Management - accessible by admin, manager, and recruiter
    Route::prefix('employees')->middleware(['role:admin|manager|recruiter'])->name('employees.')->group(function () {
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::get('/template-download', [BulkUploadTemplateController::class, 'downloadTemplate'])->name('template.download');
        Route::post('/bulk-upload', [EmployeeController::class, 'bulkUpload'])->name('bulkUpload');

        // Nested Modules for Each Employee
        Route::prefix('{employee}')->group(function () {
            // Emergency Contacts
            Route::get('emergency-contacts/edit', [EmployeeEmergencyContactController::class, 'edit'])->name('emergency-contacts.edit');
            Route::put('emergency-contacts', [EmployeeEmergencyContactController::class, 'update'])->name('emergency-contacts.update');
            Route::delete('emergency-contacts/{contact}', [EmployeeEmergencyContactController::class, 'destroy'])->name('emergency-contacts.destroy');

            // Dependents
            Route::get('dependents/edit', [EmployeeDependentController::class, 'edit'])->name('dependents.edit');
            Route::put('dependents', [EmployeeDependentController::class, 'update'])->name('dependents.update');
            Route::delete('dependents/{dependent}', [EmployeeDependentController::class, 'destroy'])->name('dependents.destroy');

            // Educations
            Route::get('educations/edit', [EmployeeEducationController::class, 'edit'])->name('educations.edit');
            Route::put('educations', [EmployeeEducationController::class, 'update'])->name('educations.update');
            Route::delete('educations/{education}', [EmployeeEducationController::class, 'destroy'])->name('educations.destroy');
        });
        Route::resource('personal_infos', PersonalInfoController::class);
        Route::resource('dependent', EmployeeDependentController::class)->except(['show']);
        Route::resource('education', EmployeeEducationController::class)->except(['show']);
    });


    // Leave Management - accessible by admin, manager, and supervisor
    Route::middleware(['role:admin|manager|supervisor'])->group(function () {
        Route::resource('leaves', LeaveController::class)->names('leaves');
        Route::resource('leave-types', LeaveTypeController::class)->parameters(['leave-types' => 'leave_type'])->names('leave_types');
        Route::resource('assign_leaves', AssignLeaveController::class)->parameters(['assign_leaves' => 'assignLeave'])->names('assign_leaves');
    });

    // Employee Leave Requests - accessible by all employees
    Route::middleware(['role:employee|admin|manager|supervisor'])->group(function () {
        Route::get('/my-leaves', [LeaveController::class, 'myLeaves'])->name('leaves.my');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
    });

    // Holiday Management - accessible by admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('holidays', HolidayController::class)->names('holidays');
        Route::resource('departments', DepartmentController::class)->names('departments');
        Route::resource('positions', PositionController::class)->names('positions');
        Route::resource('agencies', AgencyController::class)->names('agencies');
        Route::resource('cdmlevels', CDMLevelController::class)->names('cdmlevels');
        Route::resource('roles', RoleController::class)->names('roles');
        Route::get('user-roles', [RoleController::class, 'userRoles'])->name('roles.user-roles');
        Route::put('user-roles/{user}', [RoleController::class, 'updateUserRole'])->name('roles.update-user-role');
        Route::resource('role_permissions', RolePermissionController::class)->names('role_permissions');
        Route::resource('employment_types', EmploymentTypeController::class)->names('employment_types');
    });

    // Payroll Management - accessible by admin and payroll officer
    Route::middleware(['role:admin|payroll officer'])->group(function () {
        Route::prefix('excess')->name('excess.')->group(function () {
            Route::get('/', [ExcessTimeController::class, 'index'])->name('index');
            Route::post('/', [ExcessTimeController::class, 'store'])->name('store');
            Route::put('/{id}', [ExcessTimeController::class, 'update'])->name('update');
            Route::delete('/{id}', [ExcessTimeController::class, 'destroy'])->name('destroy');
        });
    });

    // File 201 Management - accessible by admin and HR
    Route::middleware(['role:admin|recruiter'])->group(function () {
        Route::get('/201-files', [File201Controller::class, 'index'])->name('file201.index');
        Route::post('/201-files', [File201Controller::class, 'store'])->name('file201.store');
        Route::delete('/201-files/{id}', [File201Controller::class, 'destroy'])->name('file201.destroy');
        Route::get('/file201/{id}/attachment', [File201Controller::class, 'showAttachment'])->name('file201.attachment');
    });

    // Search - accessible by all authenticated users
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout - accessible by all authenticated users
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware(['role:admin'])->get('/test-role', function () {
        return 'Role middleware works!';
    });
});

// API endpoint
Route::get('/api/all-time-records', function () {
    return \App\Models\TimeRecord::with('user')
        ->orderBy('recorded_at', 'desc')
        ->get()
        ->map(function ($record) {
            return [
                'id' => $record->id,
                'user_id' => $record->user_id,
                'user_name' => $record->user ? $record->user->name : null,
                'recorded_at' => $record->recorded_at,
                'type' => $record->type,
                'status' => $record->status,
            ];
        });
});

require __DIR__ . '/auth.php';
