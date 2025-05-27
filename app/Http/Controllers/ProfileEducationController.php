<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentInfo;
use App\Models\EmployeeEducation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileEducationController extends Controller
{
    public function edit()
    {
        $employee = Auth::user()->employmentInfo;
        $employee->load('educations');
    
        $educations = $employee->educations ?? collect();
        
        // Ensure we always index at least 1 education form
        if ($educations->count() < 1) {
            $educations->push(new EmployeeEducation());
        }

        return view('profile.educations.edit', [
            'employee' => $employee,
            'employeeName' => Auth::user()->name,
            'educations' => $educations,
            'statusOptions' => [
                'graduate' => 'Graduate',
                'undergraduate' => 'Undergraduate',
            ]
        ]);
    }

    public function update(Request $request)
    {
        $employee = Auth::user()->employmentInfo;

        try {
            $validated = $request->validate([
                'educations' => 'required|array|min:1',
                'educations.*.school_name' => 'required|string|max:255',
                'educations.*.course_taken' => 'required|string|max:255',
                'educations.*.year_finished' => 'required|integer|min:1900|max:' . (date('Y') + 5),
                'educations.*.status' => 'required|in:graduate,undergraduate',
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
                        // Verify the education record belongs to this user
                        $education = EmployeeEducation::where('id', $educationData['id'])
                            ->where('employee_number', $employee->employee_number)
                            ->firstOrFail();
                        
                        $education->update($data);
                        $updatedIds[] = $educationData['id'];
                    } else {
                        $newEducation = $employee->educations()->create($data);
                        $updatedIds[] = $newEducation->id;
                    }
                }

                // Delete educations not present in the request
                $toDelete = array_diff($existingIds, $updatedIds);
                if (!empty($toDelete)) {
                    EmployeeEducation::whereIn('id', $toDelete)
                        ->where('employee_number', $employee->employee_number)
                        ->delete();
                }
            });

            return redirect()->route('profile.index')
                ->with('success', 'Education records updated successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update education records: ' . $e->getMessage());
        }
    }

    public function destroy(EmployeeEducation $education)
    {
        // Verify the education record belongs to the current user
        if ($education->employee_number !== Auth::user()->employmentInfo->employee_number) {
            abort(403);
        }
        
        $education->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Education record deleted successfully.');
    }
}