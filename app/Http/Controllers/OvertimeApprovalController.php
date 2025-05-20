<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;

class OvertimeApprovalController extends Controller
{
    /**
     * Display a listing of pending overtime requests.
     */
    public function index()
    {
        $overtimes = Overtime::where('status', 'pending')
            ->with([
                'employee.personalInfo',
                'employee.department',
                'employee.position'
            ])
            ->latest()
            ->get();

        return view('overtimes.approval.index', compact('overtimes'));
    }

    /**
     * Update the approval status of a specific overtime request.
     */
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
}
