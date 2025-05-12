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
    EmployeeController
};

use App\Http\Controllers\EmployeePersonalInfoController;
use App\Http\Controllers\EmployeeEmergencyContactController;
use App\Http\Controllers\EmployeeDependentController;
use App\Http\Controllers\EmployeeEducationController;
use App\Http\Controllers\EmployeeEmploymentHistoryController;

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController
};

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

    // Profile
  /*   Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    }); */



    // Employee Routes
Route::prefix('employees')->name('employees.')->group(function () {
    Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');

      Route::get('/template-download', [EmployeeController::class, 'downloadTemplate'])->name('template.download');
      Route::post('/bulk-upload', [EmployeeController::class, 'bulkUpload'])->name('bulkUpload');


    
    Route::resource('personal_infos', PersonalInfoController::class);
    Route::resource('emergency-contact', EmployeeEmergencyContactController::class)->except(['show']);
    Route::resource('dependent', EmployeeDependentController::class)->except(['show']);
    Route::resource('education', EmployeeEducationController::class)->except(['show']);
    Route::resource('employment-history', EmployeeEmploymentHistoryController::class)->except(['show']);
});


// Main employees resource route (this already includes the delete route)
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

    Route::get('/positions/by-cdm-level/{cdmLevel}', [PositionController::class, 'getByCdmLevel'])->name('positions.by-cdm-level');
    Route::get('/positions/{position}/cdm-level', [PositionController::class, 'getCdmLevel'])->name('positions.cdm-level');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
