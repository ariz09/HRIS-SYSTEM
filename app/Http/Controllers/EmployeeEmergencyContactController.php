<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentInfo;
use App\Models\EmployeeEmergencyContact;

class EmployeeEmergencyContactController extends Controller
{
    public function edit(EmploymentInfo $employee)
    {
        $contacts = $employee->emergencyContacts;
        
        // Ensure we always show at least 2 contact forms
        while ($contacts->count() < 2) {
            $contacts->push(new EmployeeEmergencyContact());
        }

        // Get formatted employee name
        $employeeName = 'Employee';
        if ($employee->personalInfo) {
            $lastName = strtoupper($employee->personalInfo->last_name ?? '');
            $firstName = strtoupper($employee->personalInfo->first_name ?? '');
            $employeeName = $lastName . ($firstName ? ', ' . $firstName : '');
        }

        return view('employees.emergency_contacts.edit', [
            'employee' => $employee,
            'employeeName' => $employeeName,
            'contacts' => $contacts,
            'relationshipOptions' => [
                'spouse' => 'Spouse',
                'child' => 'Child',
                'parent' => 'Parent',
                'other' => 'Other'
            ]
        ]);
    }
    public function update(Request $request, EmploymentInfo $employee)
    {
        $validated = $request->validate([
            'contacts' => 'required|array|min:2',
            'contacts.*.fullname' => 'required|string|max:255',
            'contacts.*.relationship' => 'required|in:spouse,child,parent,other',
            'contacts.*.contact_number' => 'required|string|max:20|regex:/^[0-9]+$/',
            'contacts.*.address' => 'nullable|string|max:255',
        ], [
            'contacts.min' => 'You must provide at least 2 emergency contacts.',
            'contacts.*.fullname.required' => 'The full name is required for all contacts.',
            'contacts.*.relationship.required' => 'The relationship is required for all contacts.',
            'contacts.*.contact_number.required' => 'The contact number is required for all contacts.',
        ]);

        $existingIds = $employee->emergencyContacts->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($request->contacts as $contactData) {
            $data = [
                'fullname' => strtoupper($contactData['fullname']),
                'relationship' => strtoupper($contactData['relationship']),
                'contact_number' => $contactData['contact_number'],
                'address' => strtoupper($contactData['address'] ?? null),
                'employee_number' => $employee->employee_number
            ];

            if (!empty($contactData['id'])) {
                $employee->emergencyContacts()
                    ->where('id', $contactData['id'])
                    ->update($data);
                $updatedIds[] = $contactData['id'];
            } else {
                $newContact = $employee->emergencyContacts()->create($data);
                $updatedIds[] = $newContact->id;
            }
        }

        // Delete contacts not present in the request
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            EmployeeEmergencyContact::whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('employees.edit', $employee)
            ->with('success', 'Emergency contacts updated successfully.');
    }

    public function destroy(EmploymentInfo $employee, EmployeeEmergencyContact $contact)
    {
        $contact->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Contact deleted successfully.');
    }
}