<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentInfo;
use App\Models\EmployeeDependent;
use Carbon\Carbon;

class EmployeeDependentController extends Controller
{
    public function edit(EmploymentInfo $employee)
    {
        $employee->load('dependents');
    
        $dependents = $employee->dependents ?? collect();
        
        // Ensure we always show at least 1 dependent form
        if ($dependents->count() < 1) {
            $dependents->push(new EmployeeDependent());
        }

        // Get formatted employee name
        $employeeName = 'Employee';
        if ($employee->personalInfo) {
            $lastName = strtoupper($employee->personalInfo->last_name ?? '');
            $firstName = strtoupper($employee->personalInfo->first_name ?? '');
            $employeeName = $lastName . ($firstName ? ', ' . $firstName : '');
        }

        return view('employees.dependents.edit', [
            'employee' => $employee,
            'employeeName' => $employeeName,
            'dependents' => $dependents,
            'dependentTypeOptions' => [
                'spouse' => 'Spouse',
                'child' => 'Child',
                'parent' => 'Parent',
                'sibling' => 'Sibling',
                'other' => 'Other'
            ]
        ]);
    }

    public function update(Request $request, EmploymentInfo $employee)
    {
        $validated = $request->validate([
            'dependents' => 'required|array|min:1',
            'dependents.*.full_name' => 'required|string|max:255',
            'dependents.*.dependent_type' => 'required|in:spouse,child,parent,sibling,other',
            'dependents.*.birthdate' => 'required|date',
            'dependents.*.contact_number' => 'nullable|string|max:20|regex:/^[0-9]+$/',
        ], [
            'dependents.min' => 'You must provide at least 1 dependent.',
            'dependents.*.full_name.required' => 'The full name is required for all dependents.',
            'dependents.*.dependent_type.required' => 'The relationship type is required for all dependents.',
            'dependents.*.birthdate.required' => 'The birthdate is required for all dependents.',
        ]);

        $existingIds = $employee->dependents->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($request->dependents as $dependentData) {
            $data = [
                'full_name' => strtoupper($dependentData['full_name']),
                'dependent_type' => $dependentData['dependent_type'],
                'birthdate' => Carbon::parse($dependentData['birthdate']),
                'contact_number' => $dependentData['contact_number'] ?? null,
                'employee_number' => $employee->employee_number
            ];

            if (!empty($dependentData['id'])) {
                $employee->dependents()
                    ->where('id', $dependentData['id'])
                    ->update($data);
                $updatedIds[] = $dependentData['id'];
            } else {
                $newDependent = $employee->dependents()->create($data);
                $updatedIds[] = $newDependent->id;
            }
        }

        // Delete dependents not present in the request
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            EmployeeDependent::whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('employees.edit', $employee)
            ->with('success', 'Dependents updated successfully.');
    }

    public function destroy(EmploymentInfo $employee, EmployeeDependent $dependent)
    {
        $dependent->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Dependent deleted successfully.');
    }
}