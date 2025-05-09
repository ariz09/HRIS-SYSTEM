<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Agency;
use App\Models\Department;
use App\Models\CDMLevel;
use App\Models\Position;
use App\Models\EmploymentStatus;
use App\Models\Dependent;
use App\Models\EmployeeEducation;
use App\Models\EmergencyContact;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position', 'agency'])->get();
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
        
        DB::transaction(function () use ($validated, $request) {
            $employee = Employee::create($validated);
            $this->saveRelationships($employee, $request);
        });

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
            'formOptions' => $formOptions
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployee($request, $employee);
        
        DB::transaction(function () use ($employee, $validated, $request) {
            $employee->update($validated);
            $this->saveRelationships($employee, $request, true);
        });

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
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
            'employmentHistories'
        ]);
        
        return view('employees.show', compact('employee'));
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
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'basic_pay' => 'required|numeric|min:0',
            'rata' => 'nullable|numeric|min:0',
            'comm_allowance' => 'nullable|numeric|min:0',
            'transpo_allowance' => 'nullable|numeric|min:0',
            'parking_toll_allowance' => 'nullable|numeric|min:0',
            'clothing_allowance' => 'nullable|numeric|min:0',
            'sss_number' => 'nullable|string|max:50',
            'philhealth_number' => 'nullable|string|max:50',
            'pag_ibig_number' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:employees,email' . ($employee ? ','.$employee->id : ''),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'atm_account_number' => 'nullable|string|max:50',
            'bank' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'civil_status' => 'nullable|string|in:single,married,widowed,divorced',
            
            // Relationship validation rules
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.school_name' => 'required|string|max:255',
            'educations.*.year_completed' => 'nullable|numeric',
            'dependents.*.name' => 'required|string|max:255',
            'dependents.*.relationship' => 'required|string|max:255',
            'dependents.*.birthdate' => 'nullable|date',
            
            // Emergency Contacts - mandatory 2 contacts
            'emergency_contacts' => 'required|array|size:2',
            'emergency_contacts.0.name' => 'required|string|max:255',
            'emergency_contacts.0.relationship' => 'required|string|max:255',
            'emergency_contacts.0.phone' => 'required|string|max:20',
            'emergency_contacts.1.name' => 'required|string|max:255',
            'emergency_contacts.1.relationship' => 'required|string|max:255',
            'emergency_contacts.1.phone' => 'required|string|max:20',
            
            'employment_histories.*.company_name' => 'required|string|max:255',
            'employment_histories.*.position' => 'required|string|max:255',
            'employment_histories.*.start_date' => 'required|date',
            'employment_histories.*.end_date' => 'nullable|date',
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
                    $employee->dependents()->create([
                        'name' => $dependent['name'],
                        'relationship' => $dependent['relationship'],
                        'birthdate' => $dependent['birthdate'] ?? null,
                    ]);
                }
            }
        }

        // Educations
        if ($request->has('educations')) {
            if ($isUpdate) $employee->educations()->delete();
            foreach ($request->educations as $education) {
                if (!empty($education['degree']) && !empty($education['school_name'])) {
                    $employee->educations()->create([
                        'degree' => $education['degree'],
                        'school_name' => $education['school_name'],
                        'year_completed' => $education['year_completed'] ?? null,
                    ]);
                }
            }
        }

        // Emergency Contacts - mandatory 2 contacts
        if ($isUpdate) {
            $employee->emergencyContacts()->delete();
        }
        
        // Save exactly 2 emergency contacts
        foreach ($request->emergency_contacts as $contact) {
            $employee->emergencyContacts()->create([
                'name' => $contact['name'],
                'relationship' => $contact['relationship'],
                'phone' => $contact['phone'],
            ]);
        }

        // Employment History
        if ($request->has('employment_histories')) {
            if ($isUpdate) $employee->employmentHistories()->delete();
            foreach ($request->employment_histories as $history) {
                if (!empty($history['company_name'])) {
                    $employee->employmentHistories()->create([
                        'company_name' => $history['company_name'],
                        'position' => $history['position'],
                        'start_date' => $history['start_date'],
                        'end_date' => $history['end_date'] ?? null,
                    ]);
                }
            }
        }
    }
}