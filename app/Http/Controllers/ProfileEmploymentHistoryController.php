<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEmploymentHistory;
use App\Models\EmploymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileEmploymentHistoryController extends Controller
{
    public function edit()
    {
        $employee = Auth::user()->employmentInfo;
        $histories = $employee->employmentHistories()->orderBy('start_date', 'desc')->get();

        // Ensure we always index at least one empty form
        if ($histories->isEmpty()) {
            $histories->push(new EmployeeEmploymentHistory());
        }

        return view('profile.employment-history.edit', [
            'employee' => $employee,
            'employeeName' => Auth::user()->name,
            'histories' => $histories,
        ]);
    }

    public function update(Request $request)
    {
        $employee = Auth::user()->employmentInfo;
    
        $validated = $request->validate([
            'histories' => 'required|array|min:1',
            'histories.*.id' => 'nullable|exists:employee_employment_histories,id,employee_number,'.$employee->employee_number,
            'histories.*.job_title' => 'required|string|max:255',
            'histories.*.company_name' => 'required|string|max:255',
            'histories.*.company_address' => 'required|string|max:255',
            'histories.*.start_date' => 'required|date',
            'histories.*.end_date' => 'nullable|date|after:histories.*.start_date',
        ]);
    
        $existingIds = $employee->employmentHistories()->pluck('id')->toArray();
        $updatedIds = [];
    
        foreach ($request->histories as $historyData) {
            $data = [
                'employee_number' => $employee->employee_number,
                'job_title' => strtoupper($historyData['job_title']),
                'company_name' => strtoupper($historyData['company_name']),
                'company_address' => strtoupper($historyData['company_address']),
                'start_date' => $historyData['start_date'],
                'end_date' => $historyData['end_date'] ?? null,
            ];
    
            if (!empty($historyData['id'])) {
                // Verify the history belongs to this user
                $history = EmployeeEmploymentHistory::where('id', $historyData['id'])
                    ->where('employee_number', $employee->employee_number)
                    ->firstOrFail();
                
                $history->update($data);
                $updatedIds[] = $historyData['id'];
            } else {
                $newHistory = $employee->employmentHistories()->create($data);
                $updatedIds[] = $newHistory->id;
            }
        }
    
        // Delete histories not present in the request
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            EmployeeEmploymentHistory::whereIn('id', $toDelete)
                ->where('employee_number', $employee->employee_number)
                ->delete();
        }
    
        return redirect()->route('profile.index')
            ->with('success', 'Employment histories updated successfully.');
    }
    
    public function destroy(EmployeeEmploymentHistory $history)
    {
        // Verify the history belongs to the current user
        if ($history->employee_number !== Auth::user()->employmentInfo->employee_number) {
            abort(403);
        }
        
        $history->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Employment history deleted successfully.');
    }
}