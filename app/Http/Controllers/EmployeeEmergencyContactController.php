<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmployeeEmergencyContactController extends Controller
{
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'present_address' => 'nullable|string|max:255',
        ]);

        $employee->emergencyContacts()->create($validated);

        return redirect()->route('employees.edit', $employee)->with('success', 'Emergency contact added successfully.');
    }

    public function update(Request $request, EmergencyContact $emergencyContact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'present_address' => 'nullable|string|max:255',
        ]);

        $emergencyContact->update($validated);

        return redirect()->route('employees.edit', $emergencyContact->employee)->with('success', 'Emergency contact updated successfully.');
    }

    public function destroy(EmergencyContact $emergencyContact)
    {
        $emergencyContact->delete();

        return redirect()->route('employees.edit', $emergencyContact->employee)->with('success', 'Emergency contact deleted successfully.');
    }
}
