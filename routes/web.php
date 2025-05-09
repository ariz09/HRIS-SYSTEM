<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    EmployeeController,
    DepartmentController,
    LeaveController,
    LeaveTypeController,
    PositionController,
    SearchController,
    AssignLeaveController,
    AgencyController,
    CDMLevelController ,
    DashboardController,
    EmploymentTypeController,
    HolidayController,
    RoleController,
    RolePermissionController,
    AdminController,
    LeaveApplicationController
};

use App\Http\Controllers\EmployeePersonalInfoController;
use App\Http\Controllers\EmployeeEmergencyContactController;
use App\Http\Controllers\EmployeeDependentController;
use App\Http\Controllers\EmployeeEducationController;
use App\Http\Controllers\EmployeeEmploymentHistoryController;

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    AdminAuthenticatedSessionController
};
use App\Models\EmploymentType;

// Redirect root to login
Route::redirect('/', '/login');


// Guest Routes (Unauthenticated)
Route::middleware('guest')->group(function () {

    // Regular User Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Admin Login
    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    });
});


Route::middleware(['auth'])->group(function () {
    Route::get('employees/bulk-upload', [EmployeeController::class, 'bulkUploadForm'])->name('employees.bulk-upload');
    Route::post('employees/bulk-upload/process', [EmployeeController::class, 'bulkUploadProcess'])->name('employees.bulk-upload.process');
    });

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('admin.dashboard.action');

    // Employee Routes
    Route::resource('employees', EmployeeController::class)->names([
        'index' => 'admin.employees.index',
        'create' => 'admin.employees.create',
        'store' => 'admin.employees.store',
        'show' => 'admin.employees.show',
        'edit' => 'admin.employees.edit',
        'update' => 'admin.employees.update',
        'destroy' => 'admin.employees.destroy',
    ]);

    // Bulk Upload
    Route::get('employees/bulk-upload', [EmployeeController::class, 'bulkUploadForm'])->name('admin.employees.bulk-upload');
    Route::post('employees/bulk-upload/process', [EmployeeController::class, 'bulkUploadProcess'])->name('admin.employees.bulk-upload.process');

    // General Resources
    Route::resource('departments', DepartmentController::class)->names('admin.departments');
    Route::resource('positions', PositionController::class)->names('admin.positions');
    Route::resource('agencies', AgencyController::class)->names('admin.agencies');
    Route::resource('cdmlevels', CDMLevelController::class)->names('admin.cdmlevels');
    Route::resource('roles', RoleController::class)->names('admin.roles');
    Route::resource('role_permissions', RolePermissionController::class)->names('admin.role_permissions');
    Route::resource('employment_types', EmploymentTypeController::class)->names('admin.employment_types');

    // Employee Details Routes
    Route::prefix('employees')->name('admin.employees.')->group(function () {
        Route::put('{employee}/personal-info', [EmployeePersonalInfoController::class, 'update'])->name('personal-info.update');
        Route::resource('emergency-contact', EmployeeEmergencyContactController::class)->except(['show']);
        Route::resource('dependent', EmployeeDependentController::class)->except(['show']);
        Route::resource('education', EmployeeEducationController::class)->except(['show']);
        Route::resource('employment-history', EmployeeEmploymentHistoryController::class)->except(['show']);
    });

    // Position Routes
    Route::get('/positions/by-cdm-level/{cdmLevel}', [PositionController::class, 'getByCdmLevel'])->name('admin.positions.by-cdm-level');
    Route::get('/positions/{position}/cdm-level', [PositionController::class, 'getCdmLevel'])->name('admin.positions.cdm-level');

    // Leave Management
    Route::resource('leaves', LeaveController::class)->names([
        'index' => 'admin.leaves.index',
        'create' => 'admin.leaves.create',
        'store' => 'admin.leaves.store',
        'edit' => 'admin.leaves.edit',
        'update' => 'admin.leaves.update',
        'destroy' => 'admin.leaves.destroy',
    ]);
    Route::resource('leave-types', LeaveTypeController::class)
        ->parameters(['leave-types' => 'leave_type'])
        ->names('admin.leave_types');
    Route::resource('assign_leaves', AssignLeaveController::class)
        ->parameters(['assign_leaves' => 'assignLeave'])
        ->names('admin.assign_leaves');

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->names([
        'index' => 'admin.holidays.index',
        'create' => 'admin.holidays.create',
        'store' => 'admin.holidays.store',
        'edit' => 'admin.holidays.edit',
        'update' => 'admin.holidays.update',
        'destroy' => 'admin.holidays.destroy',
    ]);

    // Profile
    Route::prefix('profile')->name('admin.profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('admin.search');

    // Logout
    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

require __DIR__ . '/auth.php';
