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
    TimeRecordController
};

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Redirect root to login
Route::redirect('/', '/login');

// Guest Routes (Unauthenticated)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

    // Time Records
    Route::prefix('time-records')->group(function () {
        Route::get('/', [TimeRecordController::class, 'index'])->name('time-records.index');
        Route::post('/time-in', [TimeRecordController::class, 'timeIn'])->name('time-in');
        Route::post('/time-out', [TimeRecordController::class, 'timeOut'])->name('time-out');
        Route::post('/time-out/update-status', [TimeRecordController::class, 'updateTimeInStatus'])->name('time-out.update-status');
        Route::get('/my', [TimeRecordController::class, 'myTimeRecords'])->name('time-records.my');
        Route::get('/all', [TimeRecordController::class, 'allTimeRecords'])->name('time-records.all');
    });

    Route::prefix('employees/{employee}/employment-histories')->group(function () {
        Route::get('edit', [EmployeeEmploymentHistoryController::class, 'edit'])
            ->name('employees.employment-histories.edit');
        Route::put('update', [EmployeeEmploymentHistoryController::class, 'update'])
            ->name('employees.employment-histories.update');
        Route::delete('{history}', [EmployeeEmploymentHistoryController::class, 'destroy'])
            ->name('employees.employment-histories.destroy');
    });

    // Employee Routes
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::get('/template-download', [EmployeeController::class, 'downloadTemplate'])->name('template.download');
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

    // Main Employee resource route
    Route::resource('employees', EmployeeController::class);

    // Leave Management
    Route::resource('leaves', LeaveController::class)->names('leaves');
    Route::resource('leave-types', LeaveTypeController::class)->parameters(['leave-types' => 'leave_type'])->names('leave_types');
    Route::resource('assign_leaves', AssignLeaveController::class)->parameters(['assign_leaves' => 'assignLeave'])->names('assign_leaves');

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->names('holidays');

    // General Management
    Route::resource('departments', DepartmentController::class)->names('departments');
    Route::resource('positions', PositionController::class)->names('positions');
    Route::resource('agencies', AgencyController::class)->names('agencies');
    Route::resource('cdmlevels', CDMLevelController::class)->names('cdmlevels');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('role_permissions', RolePermissionController::class)->names('role_permissions');
    Route::resource('employment_types', EmploymentTypeController::class)->names('employment_types');

    // Position Utilities
    Route::get('/positions/by-cdm-level/{cdmLevel}', [PositionController::class, 'getByCdmLevel'])->name('positions.by-cdm-level');
    Route::get('/positions/{position}/cdm-level', [PositionController::class, 'getCdmLevel'])->name('positions.cdm-level');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
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
