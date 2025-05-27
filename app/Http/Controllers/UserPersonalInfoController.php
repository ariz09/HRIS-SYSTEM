<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\EmploymentInfo;
use App\Models\CompensationPackage;
use App\Models\EmployeeDependent;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeEducation;
use App\Models\EmployeeEmploymentHistory;
class UserPersonalInfoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employmentInfo = EmploymentInfo::with([
            'agency',
            'department',
            'cdmLevel',
            'position',
            'employmentType',
            'employmentStatus'
        ])->where('user_id', $user->id)->first();

        $employeeNumber = optional($employmentInfo)->employee_number;

        return view('profile.index', [ 
            'personalInfo' => PersonalInfo::where('user_id', $user->id)->first(),
            'employmentInfo' => $employmentInfo,
            'compensation' => $employeeNumber ? CompensationPackage::where('employee_number', $employeeNumber)->first() : null,
            'agency' => $employeeNumber ? EmploymentInfo::where('employee_number', $employeeNumber)->value('agency_id') : null,
            'dependents' => $employeeNumber ? EmployeeDependent::where('employee_number', $employeeNumber)->get() : collect(),
            'emergencyContacts' => $employeeNumber ? EmployeeEmergencyContact::where('employee_number', $employeeNumber)->get() : collect(),
            'education' => $employeeNumber ? EmployeeEducation::where('employee_number', $employeeNumber)->get() : collect(),
            'employmentHistory' => $employeeNumber ? EmployeeEmploymentHistory::where('employee_number', $employeeNumber)->get() : collect(),
            //'governmentIds' => $employeeNumber ? GovernmentId::where('employee_number', $employeeNumber)->first() : null,
        ]);
    }




    
        public function show()
        {
            $user = Auth::user();
            $employeeNumber = EmploymentInfo::where('userid', $user->id)->value('employee_number');

            return view('profile.index', [
                'personalInfo' => PersonalInfo::where('userid', $user->id)->first(),
                'employmentInfo' => EmploymentInfo::where('userid', $user->id)->first(),
                'compensation' => CompensationPackage::where('employee_number', $employeeNumber)->first(),
                'dependents' => EmployeeDependent::where('employee_number', $employeeNumber)->get(),
                'emergencyContacts' => EmployeeEmergencyContact::where('employee_number', $employeeNumber)->get(),
                'education' => EmployeeEducation::where('employee_number', $employeeNumber)->get(),
                'employmentHistory' => EmployeeEmploymentHistory::where('employee_number', $employeeNumber)->get(),
                //'governmentIds' => GovernmentId::where('employee_number', $employeeNumber)->first(),
            ]);
        }
    public function edit()
    {
        $user = Auth::user();
        $personalInfo = $user->personalInfo;

        if (!$personalInfo) {
            abort(404, 'Personal information not found');
        }

        return view('profile.edit-personal-info', compact('personalInfo'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $personalInfo = $user->personalInfo;

        if (!$personalInfo) {
            abort(404, 'Personal information not found');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_suffix' => 'nullable|string|max:20',
            'preferred_name' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'birthday' => 'required|date',
            'email' => 'required|email|unique:personal_infos,email,'.$personalInfo->id,
            'phone_number' => 'required|string|max:20',
            'civil_status' => 'required|string|in:Single,Married,Divorced,Widowed,Separated',
            'address' => 'required|string|max:500',
        ]);

        // Convert names to uppercase
        $validated['first_name'] = strtoupper($validated['first_name']);
        $validated['middle_name'] = $validated['middle_name'] ? strtoupper($validated['middle_name']) : null;
        $validated['last_name'] = strtoupper($validated['last_name']);
        $validated['name_suffix'] = $validated['name_suffix'] ? strtoupper($validated['name_suffix']) : null;
        $validated['preferred_name'] = $validated['preferred_name'] ? strtoupper($validated['preferred_name']) : null;

        $personalInfo->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Personal information updated successfully');
    }
}