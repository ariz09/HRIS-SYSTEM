<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EducationEducation;
use App\Models\EmployeeEducation;
// Ensure the Education model exists in the App\Models namespace
use Illuminate\Http\Request;

class EmployeeEducationController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'course_taken' => 'required|string|max:255',
            'inclusive_dates' => 'nullable|string|max:50',
            
        ]);

        $employee->education()->create($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Education record added successfully.');
    }

    public function update(Request $request, EmployeeEducation $education)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'course_taken' => 'required|string|max:255',
            'inclusive_dates' => 'nullable|string|max:50',
        
        ]);

        $education->update($validated);

        return redirect()->route('employees.edit', $education->employee)->with('success', 'Education record updated successfully.');
    }

    public function destroy(EmployeeEducation $education)
    {
        $education->delete();

        return redirect()->route('employees.edit', $education->employee)->with('success', 'Education record deleted successfully.');
    }
}
