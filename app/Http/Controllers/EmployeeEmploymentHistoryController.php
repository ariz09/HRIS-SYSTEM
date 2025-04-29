<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmploymentHistory;
use Illuminate\Http\Request;

class EmployeeEmploymentHistoryController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position_held' => 'required|string|max:255',
            'employment_period' => 'nullable|string|max:255',
            'most_recent_salary' => 'nullable|numeric',
        ]);

        $employee->employmentHistories()->create($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Employment history added successfully.');
    }

    public function update(Request $request, EmploymentHistory $employmentHistory)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position_held' => 'required|string|max:255',
            'employment_period' => 'nullable|string|max:255',
            'most_recent_salary' => 'nullable|numeric',
        ]);

        $employmentHistory->update($validated);

        return redirect()->route('employees.edit', $employmentHistory->employee)->with('success', 'Employment history updated successfully.');
    }

    public function destroy(EmploymentHistory $employmentHistory)
    {
        $employmentHistory->delete();

        return redirect()->route('employees.edit', $employmentHistory->employee)->with('success', 'Employment history deleted successfully.');
    }
}
