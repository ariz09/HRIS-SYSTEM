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
    AdminController
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

    // Employee Routes
    Route::resource('employees', EmployeeController::class);


    // General Resources
    Route::resources([
        'departments' => DepartmentController::class,
        'positions' => PositionController::class,
        'agencies' => AgencyController::class,
        'cdmlevels' => CDMLevelController::class,
        'roles' => RoleController::class,
        'role_permissions' => RolePermissionController::class,
        'employment_types' => EmploymentTypeController::class,
    ]);

    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('create', [EmployeeController::class, 'create'])->name('create');
        Route::post('store', [EmployeeController::class, 'store'])->name('store');
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('{employee}', [EmployeeController::class, 'destroy'])->name('destroy');

        Route::put('{employee}/personal-info', [EmployeePersonalInfoController::class, 'update'])->name('personal-info.update');
        Route::resource('emergency-contact', EmployeeEmergencyContactController::class)->except(['show']);
        Route::resource('dependent', EmployeeDependentController::class)->except(['show']);
        Route::resource('education', EmployeeEducationController::class)->except(['show']);
        Route::resource('employment-history', EmployeeEmploymentHistoryController::class)->except(['show']);
    });
    Route::get('/positions/by-cdm-level/{cdmLevel}', [App\Http\Controllers\PositionController::class, 'getByCdmLevel']);
    Route::get('/positions/{position}/cdm-level', [App\Http\Controllers\PositionController::class, 'getCdmLevel']);
    Route::resource('holidays', HolidayController::class);

    Route::resource('positions', PositionController::class)
        ->parameters(['positions' => 'position'])
        ->names('positions');
    // Leave and Leave Types
    Route::resource('leaves', LeaveController::class);
    Route::resource('leave-types', LeaveTypeController::class)
        ->parameters(['leave-types' => 'leave_type'])
        ->names('leave_types');
    Route::resource('assign_leaves', AssignLeaveController::class)
        ->parameters(['assign_leaves' => 'assignLeave']);

    Route::resource('departments', DepartmentController::class)
        ->parameters(['assign_leaves' => 'assignLeave']);

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

});

// Admin Routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Add admin-only routes here
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
