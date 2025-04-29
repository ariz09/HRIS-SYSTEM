<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Dependent;
use Illuminate\Http\Request;

class EmployeeDependentController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'spouse_name' => 'nullable|string|max:255',
            'spouse_birthdate' => 'nullable|date',
            'child_name' => 'nullable|string|max:255',
            'child_birthdate' => 'nullable|date',
        ]);

        $employee->dependents()->create($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Dependent added successfully.');
    }

    public function update(Request $request, Dependent $dependent)
    {
        $validated = $request->validate([
            'spouse_name' => 'nullable|string|max:255',
            'spouse_birthdate' => 'nullable|date',
            'child_name' => 'nullable|string|max:255',
            'child_birthdate' => 'nullable|date',
        ]);

        $dependent->update($validated);

        return redirect()->route('employees.edit', $dependent->employee)->with('success', 'Dependent updated successfully.');
    }

    public function destroy(Dependent $dependent)
    {
        $dependent->delete();

        return redirect()->route('employees.edit', $dependent->employee)->with('success', 'Dependent deleted successfully.');
    }
}
