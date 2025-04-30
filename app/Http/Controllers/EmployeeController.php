<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Agency;
use App\Models\Department;
use App\Models\CDMLevel;
use App\Models\Position;
use App\Models\EmploymentStatus;
use App\Models\Dependent;
use App\Models\Education;
use App\Models\EmergencyContact;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position'])->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $formOptions = [
            'relationships' => [
                'Spouse', 'Parent', 'Child', 'Sibling',
                'Friend', 'Relative', 'Colleague', 'Other'
            ],
            'dependentRelationships' => [
                'Spouse', 'Child', 'Parent', 'Sibling',
                'Grandchild', 'Other Family', 'Other'
            ],
            'years' => range(1950, date('Y') + 5)
        ];

        return view('employees.create-edit', [
            'agencies' => Agency::all(),
            'departments' => Department::all(),
            'cdmLevels' => CDMLevel::all(),
            'positions' => Position::all(),
            'employmentStatuses' => EmploymentStatus::all(),
            'formattedEmployeeNumber' => Employee::getFormattedNextNumber(),
            'formOptions' => $formOptions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);
        $employee = Employee::create($validated);
        
        $this->saveRelationships($employee, $request);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $formOptions = [
            'relationships' => [
                'Spouse', 'Parent', 'Child', 'Sibling',
                'Friend', 'Relative', 'Colleague', 'Other'
            ],
            'dependentRelationships' => [
                'Spouse', 'Child', 'Parent', 'Sibling',
                'Grandchild', 'Other Family', 'Other'
            ],
            'years' => range(1950, date('Y') + 5)
        ];
    
        $employee->load('dependents', 'educations', 'emergencyContacts', 'employmentHistories');
    
        return view('employees.create-edit', [
            'employee' => $employee,
            'agencies' => Agency::all(),
            'departments' => Department::all(),
            'cdmLevels' => CDMLevel::all(),
            'positions' => Position::all(),
            'employmentStatuses' => EmploymentStatus::all(),
            'employeeNumber' => $employee->employee_number,
            'formOptions' => $formOptions // Add this line
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee);
        $employee->update($validated);
        
        $this->saveRelationships($employee, $request, true);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    protected function validateEmployee(Request $request, Employee $employee = null)
    {
        $rules = [
            'employee_number' => 'required|numeric|max:99999|unique:employees,employee_number' . ($employee ? ','.$employee->id : ''),
            'agency_id' => 'required|exists:agencies,id',
            'department_id' => 'required|exists:departments,id',
            'cdm_level_id' => 'required|exists:cdm_levels,id',
            'position_id' => 'required|exists:positions,id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name_suffix' => 'nullable|string|max:50',
            'alias' => 'nullable|string|max:255',
            'hiring_date' => 'required|date',
            'last_day' => 'nullable|date',
            'employment_status' => 'required|string|max:50',
            'basic_pay' => 'required|numeric|min:0',
            'rata' => 'nullable|numeric|min:0',
            'transpo_allowance' => 'nullable|numeric|min:0',
            'sss_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'pagibig_number' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:employees,email' . ($employee ? ','.$employee->id : ''),
            'phone_number' => 'nullable|string|max:20',
            'atm_account_number' => 'nullable|string|max:50',
            'bank' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'civil_status' => 'nullable|string|max:50',
        ];

        return $request->validate($rules);
    }

    protected function saveRelationships(Employee $employee, Request $request, $isUpdate = false)
    {
        // Dependents
        if ($request->has('dependents')) {
            if ($isUpdate) $employee->dependents()->delete();
            foreach ($request->dependents as $dependent) {
                if (!empty($dependent['name'])) {
                    $employee->dependents()->create($dependent);
                }
            }
        }

        // Educations
        if ($request->has('educations')) {
            if ($isUpdate) $employee->educations()->delete();
            foreach ($request->educations as $education) {
                if (!empty($education['degree'])) {
                    $employee->educations()->create($education);
                }
            }
        }

        // Emergency Contacts
        if ($request->has('emergency_contacts')) {
            if ($isUpdate) $employee->emergencyContacts()->delete();
            foreach ($request->emergency_contacts as $contact) {
                if (!empty($contact['name'])) {
                    $employee->emergencyContacts()->create($contact);
                }
            }
        }

        // Employment History
        if ($request->has('employment_histories')) {
            if ($isUpdate) $employee->employmentHistories()->delete();
            foreach ($request->employment_histories as $history) {
                if (!empty($history['company_name'])) {
                    $employee->employmentHistories()->create($history);
                }
            }
        }
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

}