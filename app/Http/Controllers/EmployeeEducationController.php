<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentInfo;
use App\Models\EmployeeEducation;
use Illuminate\Support\Facades\DB;

class EmployeeEducationController extends Controller
{
    public function edit(EmploymentInfo $employee)
    {
        $employee->load('educations');
    
        $educations = $employee->educations ?? collect();
        
        // Ensure we always show at least 1 education form
        if ($educations->count() < 1) {
            $educations->push(new EmployeeEducation());
        }

        // Get formatted employee name
        $employeeName = 'Employee';
        if ($employee->personalInfo) {
            $lastName = strtoupper($employee->personalInfo->last_name ?? '');
            $firstName = strtoupper($employee->personalInfo->first_name ?? '');
            $employeeName = $lastName . ($firstName ? ', ' . $firstName : '');
        }

        return view('employees.educations.edit', [
            'employee' => $employee,
            'employeeName' => $employeeName,
            'educations' => $educations,
            'statusOptions' => [
                'graduate' => 'Graduate',      // Match database enum
                'undergraduate' => 'Undergraduate',
            ]
        ]);
    }

    public function update(Request $request, EmploymentInfo $employee)
    {

        try {
            \Log::debug('Education Form Data:', $request->all());
            
            $validated = $request->validate([
                'educations' => 'required|array|min:1',
                'educations.*.school_name' => 'required|string|max:255',
                'educations.*.course_taken' => 'required|string|max:255',
                'educations.*.year_finished' => 'required|integer|min:1900|max:' . (date('Y') + 5),
                'educations.*.status' => 'required|in:graduate,undergraduate', // Match database enum
            ], [
                'educations.min' => 'You must provide at least 1 education record.',
                'educations.*.school_name.required' => 'The school name is required for all education records.',
                'educations.*.course_taken.required' => 'The course taken is required for all education records.',
                'educations.*.year_finished.required' => 'The year finished is required for all education records.',
            ]);

            DB::transaction(function () use ($employee, $request) {
                $existingIds = $employee->educations->pluck('id')->toArray();
                $updatedIds = [];

                foreach ($request->educations as $educationData) {
                    $data = [
                        'school_name' => strtoupper($educationData['school_name']),
                        'course_taken' => strtoupper($educationData['course_taken']),
                        'year_finished' => $educationData['year_finished'],
                        'status' => $educationData['status'],
                        'employee_number' => $employee->employee_number
                    ];

                    if (!empty($educationData['id'])) {
                        $employee->educations()
                            ->where('id', $educationData['id'])
                            ->update($data);
                        $updatedIds[] = $educationData['id'];
                    } else {
                        $newEducation = $employee->educations()->create($data);
                        $updatedIds[] = $newEducation->id;
                    }
                }

                // Delete educations not present in the request
                $toDelete = array_diff($existingIds, $updatedIds);
                if (!empty($toDelete)) {
                    EmployeeEducation::whereIn('id', $toDelete)->delete();
                }
            });

            return redirect()->route('employees.edit', $employee)
                ->with('success', 'Education records updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update education records: ' . $e->getMessage());
        }
    }

    public function destroy(EmploymentInfo $employee, EmployeeEducation $education)
    {
        $education->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Education record deleted successfully.');
    }
}