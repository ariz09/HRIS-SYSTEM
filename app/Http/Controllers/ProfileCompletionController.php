<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\EmployeeDependent;
use App\Models\EmployeeEducation;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeEmploymentHistory;
use App\Models\EmployeeInfo;
use App\Models\EmploymentStatus;

class ProfileCompletionController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $employeeInfo = $user->employeeInfo;

        return view('profile.complete', [
            'user' => $user,
            'employeeInfo' => $employeeInfo,
            'dependents' => $employeeInfo?->dependents ?? collect(),
            'educations' => $employeeInfo?->educations ?? collect(),
            'emergencyContact' => $employeeInfo?->emergencyContact,
            'employmentHistories' => $employeeInfo?->employmentHistories ?? collect(),
            'employmentInfo' => $employeeInfo?->employmentInfo,
            'employmentStatus' => $employeeInfo?->employmentStatus,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $employeeInfo = $user->employeeInfo;

        // Store Dependents
        if ($request->has('dependents')) {
            foreach ($request->dependents as $dependent) {
                EmployeeDependent::updateOrCreate(
                    ['id' => $dependent['id'] ?? null],
                    array_merge($dependent, ['employee_info_id' => $employeeInfo->id])
                );
            }
        }

        // Store Education
        if ($request->has('educations')) {
            foreach ($request->educations as $education) {
                EmployeeEducation::updateOrCreate(
                    ['id' => $education['id'] ?? null],
                    array_merge($education, ['employee_info_id' => $employeeInfo->id])
                );
            }
        }

        // Store Emergency Contact
        if ($request->has('emergency_contact')) {
            EmployeeEmergencyContact::updateOrCreate(
                ['employee_info_id' => $employeeInfo->id],
                $request->emergency_contact
            );
        }

        // Store Employment History
        if ($request->has('employment_histories')) {
            foreach ($request->employment_histories as $history) {
                EmployeeEmploymentHistory::updateOrCreate(
                    ['id' => $history['id'] ?? null],
                    array_merge($history, ['employee_info_id' => $employeeInfo->id])
                );
            }
        }

        // Store Employment Info
        if ($request->has('employment_info')) {
            EmployeeInfo::updateOrCreate(
                ['id' => $employeeInfo->id],
                $request->employment_info
            );
        }

        // Store Employment Status
        if ($request->has('employment_status')) {
            EmploymentStatus::updateOrCreate(
                ['employee_info_id' => $employeeInfo->id],
                $request->employment_status
            );
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Profile completed successfully!');
    }
}
