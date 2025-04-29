<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeePersonalInfoController extends Controller
{
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'middle_name' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Personal information updated successfully.');
    }
}
