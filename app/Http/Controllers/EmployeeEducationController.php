<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Education;
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'school' => 'required|string|max:255',
            'course_taken' => 'required|string|max:255',
            'inclusive_dates' => 'nullable|string|max:255',
            'undergraduate' => 'required|boolean',
        ]);

        $employee->education()->create($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Education record added successfully.');
    }

    public function update(Request $request, Education $education)
    {
        $validated = $request->validate([
            'school' => 'required|string|max:255',
            'course_taken' => 'required|string|max:255',
            'inclusive_dates' => 'nullable|string|max:255',
            'undergraduate' => 'required|boolean',
        ]);

        $education->update($validated);

        return redirect()->route('employees.edit', $education->employee)->with('success', 'Education record updated successfully.');
    }

    public function destroy(Education $education)
    {
        $education->delete();

        return redirect()->route('employees.edit', $education->employee)->with('success', 'Education record deleted successfully.');
    }
}
