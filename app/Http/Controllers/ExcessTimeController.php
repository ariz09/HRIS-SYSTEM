<?php

namespace App\Http\Controllers;

use App\Models\Excesstime;
use App\Models\EmploymentInfo;
use Illuminate\Http\Request;

class ExcessTimeController extends Controller
{
    public function index()
    {
        $excesstimes = ExcessTime::with([
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

        return view('excess.index', compact('excesstimes', 'employees'));
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

        Excesstime::create($validated);

        return redirect()->route('excess.index')
            ->with('success', 'Excess request submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status'  => 'required|in:approved,declined',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $excesstime = Excesstime::findOrFail($id);

        $excesstime->status = $validated['status'];
        $excesstime->remarks = $validated['remarks'] ?? null;
        $excesstime->save();

        return redirect()->route('excess.index')
                         ->with('success', 'Excess request has been ' . $validated['status'] . '.');
    }

    public function destroy(Excesstime $excesstime)
    {
        $excesstime->delete();

        return redirect()->route('excess.index')
            ->with('success', 'excesstime request deleted successfully.');
    }
}
