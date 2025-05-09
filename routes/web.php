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
    CDMLevelController,
    DashboardController,
    EmploymentTypeController,
    HolidayController,
    RoleController,
    RolePermissionController
};

use App\Http\Controllers\{
    EmployeePersonalInfoController,
    EmployeeEmergencyContactController,
    EmployeeDependentController,
    EmployeeEducationController,
    EmployeeEmploymentHistoryController
};

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    AdminAuthenticatedSessionController
};

// Redirect root to login
Route::redirect('/', '/login');


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
    });
});

Route::middleware(['auth'])->group(function () {
    // Employee Bulk Upload
    Route::get('employees/bulk-upload', [EmployeeController::class, 'bulkUploadForm'])->name('employees.bulk-upload');
    Route::post('employees/bulk-upload/process', [EmployeeController::class, 'bulkUploadProcess'])->name('employees.bulk-upload.process');

    Route::prefix('employees')->group(function () {
        // Main resource routes
        Route::resource('/', EmployeeController::class)->except(['create', 'edit']);
        Route::get('create/personal', [EmployeeController::class, 'createPersonal'])
        ->name('employees.personal.create');
        
        // Personal Information
        Route::get('personal/edit', [EmployeeController::class, 'editPersonal'])
            ->name('employees.personal.edit');
        Route::put('personal/update', [EmployeeController::class, 'updatePersonal'])
            ->name('employees.personal.update');
        
        // Government IDs
        Route::get('government/edit', [EmployeeController::class, 'editGovernment'])
            ->name('employees.government.edit');
        Route::put('government/update', [EmployeeController::class, 'updateGovernment'])
            ->name('employees.government.update');
        
        // Employment
        Route::get('employment/edit', [EmployeeController::class, 'editEmployment'])
            ->name('employees.employment.edit');
        Route::put('employment/update', [EmployeeController::class, 'updateEmployment'])
            ->name('employees.employment.update');
    
        // Contact Information
        Route::get('{employee}/contact', [EmployeeController::class, 'editContact'])
            ->name('employees.contact.edit');
        Route::put('{employee}/contact', [EmployeeController::class, 'updateContact'])
            ->name('employees.contact.update');
    
        // Compensation
        Route::get('{employee}/compensation', [EmployeeController::class, 'editCompensation'])
            ->name('employees.compensation.edit');
        Route::put('{employee}/compensation', [EmployeeController::class, 'updateCompensation'])
            ->name('employees.compensation.update');
    
        // Education
        Route::get('{employee}/education', [EmployeeController::class, 'editEducation'])
            ->name('employees.education.edit');
        Route::put('{employee}/education', [EmployeeController::class, 'updateEducation'])
            ->name('employees.education.update');
    
        // Dependents
        Route::get('{employee}/dependents', [EmployeeController::class, 'editDependents'])
            ->name('employees.dependents.edit');
        Route::put('{employee}/dependents', [EmployeeController::class, 'updateDependents'])
            ->name('employees.dependents.update');
    
        // Emergency Contacts
        Route::get('{employee}/emergency', [EmployeeController::class, 'editEmergency'])
            ->name('employees.emergency.edit');
        Route::put('{employee}/emergency', [EmployeeController::class, 'updateEmergency'])
            ->name('employees.emergency.update');
    
        // Employment History
        Route::get('{employee}/history', [EmployeeController::class, 'editHistory'])
            ->name('employees.history.edit');
        Route::put('{employee}/history', [EmployeeController::class, 'updateHistory'])
            ->name('employees.history.update');
    });


    // General Resources
    Route::resources([
        'departments' => DepartmentController::class,
        'positions' => PositionController::class,
        'agencies' => AgencyController::class,
        'cdmlevels' => CDMLevelController::class,
        'roles' => RoleController::class,
        'role_permissions' => RolePermissionController::class,
        'employment_types' => EmploymentTypeController::class,
        'holidays' => HolidayController::class
    ]);

    Route::get('/positions/by-cdm-level/{cdmLevel}', [PositionController::class, 'getByCdmLevel']);
    Route::get('/positions/{position}/cdm-level', [PositionController::class, 'getCdmLevel']);

    // Leave and Assign Leave
    Route::resource('leaves', LeaveController::class);
    Route::resource('leave-types', LeaveTypeController::class)->parameters(['leave-types' => 'leave_type'])->names('leave_types');
    Route::resource('assign_leaves', AssignLeaveController::class)->parameters(['assign_leaves' => 'assignLeave']);

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/action', [DashboardController::class, 'handleAction'])->name('dashboard.action');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin Authenticated Routes
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    // Add admin routes here
});

// Public resource registration
Route::resource('employees', EmployeeController::class);

require __DIR__ . '/auth.php';
