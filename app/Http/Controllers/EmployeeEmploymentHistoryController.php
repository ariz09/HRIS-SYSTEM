<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEmploymentHistory;
use App\Http\Requests\StoreEmployeeEmploymentHistoryRequest;
use App\Http\Requests\UpdateEmployeeEmploymentHistoryRequest;
use App\Models\EmploymentInfo;
use Illuminate\Http\Request;

class EmployeeEmploymentHistoryController extends Controller
{
    public function edit($employeeNumber)
    {
        $employee = EmploymentInfo::where('employee_number', $employeeNumber)->firstOrFail();
        $histories = $employee->employmentHistories()->orderBy('start_date', 'desc')->get();

        // Ensure we always show at least one empty form
        if ($histories->isEmpty()) {
            $histories->push(new EmployeeEmploymentHistory());
        }

        // Get formatted employee name
        $employeeName = 'Employee';
        if ($employee->personalInfo) {
            $lastName = strtoupper($employee->personalInfo->last_name ?? '');
            $firstName = strtoupper($employee->personalInfo->first_name ?? '');
            $employeeName = $lastName . ($firstName ? ', ' . $firstName : '');
        }

        return view('employees.employment_histories.edit', [
            'employee' => $employee,
            'employeeName' => $employeeName,
            'histories' => $histories,
        ]);
    }

    public function update(Request $request, $employeeNumber)
    {
        $employee = EmploymentInfo::where('employee_number', $employeeNumber)->firstOrFail();

        $validated = $request->validate([
            'histories' => 'required|array|min:1',
            'histories.*.id' => 'nullable|exists:employee_employment_histories,id',
            'histories.*.job_title' => 'required|string|max:255',
            'histories.*.job_description' => 'nullable|string',
            'histories.*.start_date' => 'required|date',
            'histories.*.end_date' => 'nullable|date|after:histories.*.start_date',
        ]);

        $existingIds = $employee->employmentHistories()->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($request->histories as $historyData) {
            $data = [
                'employee_number' => $employee->employee_number,
                'job_title' => strtoupper($historyData['job_title']),
                'job_description' => strtoupper($historyData['job_description'] ?? null),
                'start_date' => $historyData['start_date'],
                'end_date' => $historyData['end_date'] ?? null,
            ];

            if (!empty($historyData['id'])) {
                $employee->employmentHistories()
                    ->where('id', $historyData['id'])
                    ->update($data);
                $updatedIds[] = $historyData['id'];
            } else {
                $newHistory = $employee->employmentHistories()->create($data);
                $updatedIds[] = $newHistory->id;
            }
        }

        // Delete histories not present in the request
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            EmployeeEmploymentHistory::whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('employees.edit', $employee)
            ->with('success', 'Employment histories updated successfully.');
    }

    public function destroy($employeeNumber, EmployeeEmploymentHistory $history)
    {
       
            $history->delete();
            
            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'Employment History deleted successfully.');
        
    }
}