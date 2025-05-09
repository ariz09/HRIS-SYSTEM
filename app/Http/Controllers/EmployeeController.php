<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Agency;
use App\Models\Department;
use App\Models\CDMLevel;
use App\Models\Position;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // Main employee views
    public function index()
    {
        $employees = Employee::with(['department', 'position', 'agency'])->get();
        return view('employees.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department', 
            'position', 
            'agency',
            'cdmLevel',
            'employmentStatus',
            'dependents',
            'educations',
            'emergencyContacts',
            'employmentHistories',
            'contactInfo',
            'compensation',
            'employment'
        ]);
        
        return view('employees.show', compact('employee'));
    }

    // Employee creation flow
    public function create()
    {
        return view('employees.create-edit', [
            'employee' => new Employee(),
            'formattedEmployeeNumber' => Employee::getFormattedNextNumber()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'employee_number' => 'required|numeric|unique:employees'
        ]);

        $employee = Employee::create($validated);

        return redirect()->route('employees.personal.edit', $employee)
            ->with('success', 'Employee created. Please fill in personal information.');
    }


    public function edit(Employee $employee)
    {
        return view('employees.create-edit', [
            'employee' => $employee,
            'formattedEmployeeNumber' => $employee->employee_number_formatted
        ]);
    }

    // Personal Information Module
    public function createPersonal()
    {
        return view('employees.modules.personal_info', [
            'employee' => new Employee(),
            'formattedEmployeeNumber' => Employee::getFormattedNextNumber(),
            'isCreating' => true
        ]);
    }

    public function editPersonal(Employee $employee)
    {
        return view('employees.modules.personal_info', [
            'employee' => $employee,
            'formattedEmployeeNumber' => $employee->employee_number_formatted,
            'isCreating' => false
        ]);
    }

    public function updatePersonal(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female',
            'birthdate' => 'required|date',
            'civil_status' => 'required|in:Single,Married,Widowed,Separated',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employee-photos');
        }

        $employee->update($validated);

        return redirect()->route('employees.personal.edit', $employee)
        ->with('success', 'Personal information updated successfully!');

    }

    // Government IDs Module
    public function editGovernment(Employee $employee)
    {
        return view('employees.modules.government_ids', compact('employee'));
    }

    public function updateGovernment(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'sss' => 'nullable|string|max:20',
            'tin' => 'nullable|string|max:20',
            'philhealth' => 'nullable|string|max:20',
            'pagibig' => 'nullable|string|max:20',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.government.edit', $employee)
            ->with('success', 'Government IDs updated successfully!');
    }

    // Employment Module
    public function editEmployment(Employee $employee)
    {
        return view('employees.modules.employment', [
            'employee' => $employee,
            'agencies' => Agency::all(),
            'departments' => Department::all(),
            'cdmLevels' => CDMLevel::all(),
            'positions' => Position::all(),
            'employmentStatuses' => EmploymentStatus::all()
        ]);
    }

    public function updateEmployment(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'cdm_level_id' => 'required|exists:cdm_levels,id',
            'hire_date' => 'required|date',
            'bank' => 'required|string|max:255',
            'atm_account_number' => 'required|string|max:20',
        ]);
    
        // Update employment relationship
        $employee->employment()->updateOrCreate(
            ['employee_id' => $employee->id],
            $validated
        );
    
        // Update employee's main record with employment data
        $employee->update($validated);
        
        return redirect()->route('employees.employment.edit', $employee)
            ->with('success', 'Employment information updated successfully!');
    }

    // Contact Information Module
    public function editContact(Employee $employee)
    {
        return view('employees.edit.contact', compact('employee'));
    }

    public function updateContact(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $employee->contactInfo()->delete();
        $employee->contactInfo()->create($validated);

        return redirect()->route('employees.contact.edit', $employee)
            ->with('success', 'Contact information updated successfully!');
    }

    // Compensation Module
    public function editCompensation(Employee $employee)
    {
        return view('employees.modules.compensation', [
            'employee' => $employee
        ]);
    }

    public function updateCompensation(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic_pay' => 'required|numeric|min:0',
            'rata' => 'nullable|numeric|min:0',
            'comm_allowance' => 'nullable|numeric|min:0',
            'transpo_allowance' => 'nullable|numeric|min:0',
            'parking_toll_allowance' => 'nullable|numeric|min:0',
            'clothing_allowance' => 'nullable|numeric|min:0',
            'total_package' => 'required|numeric|min:0',
        ]);

        $employee->compensation()->delete();
        $employee->compensation()->create($validated);

        return redirect()->route('employees.compensation.edit', $employee)
            ->with('success', 'Compensation updated successfully!');
    }

    // Education Module
    public function editEducation(Employee $employee)
    {
        return view('employees.modules.education', [
            'employee' => $employee,
            'years' => range(1950, date('Y') + 5)
        ]);
    }

    public function updateEducation(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'educations' => 'required|array|min:1',
            'educations.*.level' => 'required|in:Elementary,High School,Vocational,College,Post Graduate',
            'educations.*.school_name' => 'required|string|max:255',
            'educations.*.degree' => 'nullable|string|max:255',
            'educations.*.year_started' => 'nullable|integer|min:1900|max:' . date('Y'),
            'educations.*.year_graduated' => 'nullable|integer|min:1900|max:' . date('Y'),
            'educations.*.honors' => 'nullable|string|max:255',
        ]);

        $employee->educations()->delete();
        $employee->educations()->createMany($validated['educations']);

        return redirect()->route('employees.education.edit', $employee)
            ->with('success', 'Education information updated successfully!');
    }

    // Dependents Module
    public function editDependents(Employee $employee)
    {
        return view('employees.modules.dependents', [
            'employee' => $employee,
            'relationships' => [
                'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Other'
            ]
        ]);
    }

    public function updateDependents(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'dependents' => 'required|array|min:1',
            'dependents.*.name' => 'required|string|max:255',
            'dependents.*.relationship' => 'required|in:Spouse,Child,Parent,Sibling,Grandchild,Other',
            'dependents.*.birthdate' => 'required|date',
            'dependents.*.contact_number' => 'nullable|string|max:20',
            'dependents.*.occupation' => 'nullable|string|max:255',
        ]);

        $employee->dependents()->delete();
        $employee->dependents()->createMany($validated['dependents']);

        return redirect()->route('employees.dependents.edit', $employee)
            ->with('success', 'Dependents updated successfully!');
    }

    // Emergency Contacts Module
    public function editEmergency(Employee $employee)
    {
        return view('employees.modules.emergency_contacts', [
            'employee' => $employee,
            'relationships' => [
                'Spouse', 'Parent', 'Child', 'Sibling', 'Friend', 'Other'
            ]
        ]);
    }

    public function updateEmergency(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'emergency_contacts' => 'required|array|min:2',
            'emergency_contacts.*.name' => 'required|string|max:255',
            'emergency_contacts.*.relationship' => 'required|in:Spouse,Parent,Child,Sibling,Friend,Other',
            'emergency_contacts.*.phone' => 'required|string|max:20',
            'emergency_contacts.*.address' => 'required|string|max:500',
        ]);

        $employee->emergencyContacts()->delete();
        $employee->emergencyContacts()->createMany($validated['emergency_contacts']);

        return redirect()->route('employees.emergency.edit', $employee)
            ->with('success', 'Emergency contacts updated successfully!');
    }

    // Employment History Module
    public function editHistory(Employee $employee)
    {
        return view('employees.modules.employment_history', [
            'employee' => $employee
        ]);
    }

    public function updateHistory(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employment_history' => 'nullable|array',
            'employment_history.*.company_name' => 'required_with:employment_history|string|max:255',
            'employment_history.*.position' => 'required_with:employment_history|string|max:255',
            'employment_history.*.start_date' => 'required_with:employment_history|date',
            'employment_history.*.end_date' => 'nullable|date|after_or_equal:employment_history.*.start_date',
            'employment_history.*.salary' => 'nullable|numeric|min:0',
            'employment_history.*.responsibilities' => 'nullable|string',
            'employment_history.*.reason_for_leaving' => 'nullable|string|max:255',
        ]);

        $employee->employmentHistories()->delete();
        
        if (!empty($validated['employment_history'])) {
            $employee->employmentHistories()->createMany($validated['employment_history']);
        }

        return redirect()->route('employees.history.edit', $employee)
            ->with('success', 'Employment history updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            $employee->dependents()->delete();
            $employee->educations()->delete();
            $employee->emergencyContacts()->delete();
            $employee->employmentHistories()->delete();
            $employee->contactInfo()->delete();
            $employee->compensation()->delete();
            $employee->employment()->delete();
            
            $employee->delete();
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}