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
    LeaveApplicationController,
    EmployeeInfoController,
    EmployeeDashboardController
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
    // Route::prefix('admin')->group(function () {
    //     Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    //     Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    // });
});


Route::middleware(['auth'])->group(function () {
    Route::get('employees/bulk-upload', [EmployeeController::class, 'bulkUploadForm'])->name('employees.bulk-upload');
    Route::post('employees/bulk-upload/process', [EmployeeController::class, 'bulkUploadProcess'])->name('employees.bulk-upload.process');
    // Employee Dashboard
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
});

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

    Route::resource('employee-info', EmployeeInfoController::class);
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
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\CheckAdminRole::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

    // Employee Routes
    Route::resource('employees', EmployeeInfoController::class)->names([
        'index' => 'employees.index',
        'create' => 'employees.create',
        'store' => 'employees.store',
        'show' => 'employees.show',
        'edit' => 'employees.edit',
        'update' => 'employees.update',
        'destroy' => 'employees.destroy',
    ]);

    // Bulk Upload
    Route::get('employees/bulk-upload', [EmployeeController::class, 'bulkUploadForm'])->name('employees.bulk-upload');
    Route::post('employees/bulk-upload/process', [EmployeeController::class, 'bulkUploadProcess'])->name('employees.bulk-upload.process');

    // General Resources
    Route::resource('departments', DepartmentController::class)->names('departments');
    Route::resource('positions', PositionController::class)->names('positions');
    Route::resource('agencies', AgencyController::class)->names('agencies');
    Route::resource('cdmlevels', CDMLevelController::class)->names('cdmlevels');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('role_permissions', RolePermissionController::class)->names('role_permissions');
    Route::resource('employment_types', EmploymentTypeController::class)->names('employment_types');

    // Employee Details Routes
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::put('{employee}/personal-info', [EmployeePersonalInfoController::class, 'update'])->name('personal-info.update');
        Route::resource('emergency-contact', EmployeeEmergencyContactController::class)->except(['show']);
        Route::resource('dependent', EmployeeDependentController::class)->except(['show']);
        Route::resource('education', EmployeeEducationController::class)->except(['show']);
        Route::resource('employment-history', EmployeeEmploymentHistoryController::class)->except(['show']);
    });

    // Position Routes
    Route::get('/positions/by-cdm-level/{cdmLevel}', [PositionController::class, 'getByCdmLevel'])->name('positions.by-cdm-level');
    Route::get('/positions/{position}/cdm-level', [PositionController::class, 'getCdmLevel'])->name('positions.cdm-level');

    // Leave Management
    Route::resource('leaves', LeaveController::class)->names([
        'index' => 'leaves.index',
        'create' => 'leaves.create',
        'store' => 'leaves.store',
        'edit' => 'leaves.edit',
        'update' => 'leaves.update',
        'destroy' => 'leaves.destroy',
    ]);
    Route::resource('leave-types', LeaveTypeController::class)
        ->parameters(['leave-types' => 'leave_type'])
        ->names('leave_types');
    Route::resource('assign_leaves', AssignLeaveController::class)
        ->parameters(['assign_leaves' => 'assignLeave'])
        ->names('assign_leaves');

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->names([
        'index' => 'holidays.index',
        'create' => 'holidays.create',
        'store' => 'holidays.store',
        'edit' => 'holidays.edit',
        'update' => 'holidays.update',
        'destroy' => 'holidays.destroy',
    ]);

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout - Move this outside the admin middleware group
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

// Move the logout route outside of any middleware groups
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/pending', function () {
    return view('auth.pending');
})->name('pending');

require __DIR__ . '/auth.php';
