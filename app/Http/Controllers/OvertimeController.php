<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\EmploymentInfo;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = Overtime::with([
                'employee.personalInfo',
                'employee.department',
                'employee.position'
            ])
            ->latest()
            ->get();

        $employees = EmploymentInfo::with([
                'personalInfo',
                'department',
                'position',
                'employmentType'
            ])
            ->orderBy('employee_number')
            ->get();

        return view('overtimes.index', compact('overtimes', 'employees'));
    }
   

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|exists:employment_infos,employee_number',
            'start'           => 'required|date',
            'end'             => 'required|date|after:start',
            'reason'          => 'required|string|max:500',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Overtime::create($validated);

        return redirect()->route('overtimes.index')
            ->with('success', 'Overtime request submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status'  => 'required|in:approved,declined',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $overtime = Overtime::findOrFail($id);

        $overtime->status = $validated['status'];
        $overtime->remarks = $validated['remarks'] ?? null;
        $overtime->save();

        return redirect()->route('overtimes.index')
                         ->with('success', 'Overtime request has been ' . $validated['status'] . '.');
    }

    public function destroy(Overtime $overtime)
    {
        $overtime->delete();

        return redirect()->route('overtimes.index')
            ->with('success', 'Overtime request deleted successfully.');
    }
}
