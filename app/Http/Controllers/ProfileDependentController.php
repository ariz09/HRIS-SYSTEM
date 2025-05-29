<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentInfo;
use App\Models\EmployeeDependent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfileDependentController extends Controller
{
    public function edit()
    {
        $employee = Auth::user()->employmentInfo;
        $employee->load('dependents');
    
        $dependents = $employee->dependents ?? collect();
        
        if ($dependents->count() < 1) {
            $dependents->push(new EmployeeDependent());
        }

        return view('profile.dependents.edit', [
            'employee' => $employee,
            'employeeName' => Auth::user()->name,
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

    public function update(Request $request)
    {
        $employee = Auth::user()->employmentInfo;
        
        $validated = $request->validate([
            'dependents' => 'required|array|min:1',
            'dependents.*.full_name' => 'required|string|max:255',
            'dependents.*.dependent_type' => 'required|in:spouse,child,parent,sibling,other',
            'dependents.*.birthdate' => 'required|date',
            'dependents.*.contact_number' => 'nullable|string|max:20|regex:/^[0-9]+$/',
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
                // Ensure the dependent belongs to this user
                $dependent = EmployeeDependent::where('id', $dependentData['id'])
                    ->where('employee_number', $employee->employee_number)
                    ->firstOrFail();
                
                $dependent->update($data);
                $updatedIds[] = $dependentData['id'];
            } else {
                $newDependent = $employee->dependents()->create($data);
                $updatedIds[] = $newDependent->id;
            }
        }

        // Delete dependents not present in the request
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            EmployeeDependent::whereIn('id', $toDelete)
                ->where('employee_number', $employee->employee_number)
                ->delete();
        }

        return redirect()->route('profile.index')
            ->with('success', 'Dependents updated successfully.');
    }

    public function destroy(EmployeeDependent $dependent)
    {
        // Verify the dependent belongs to the current user
        if ($dependent->employee_number !== Auth::user()->employmentInfo->employee_number) {
            abort(403);
        }
        
        $dependent->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Dependent deleted successfully.');
    }
}