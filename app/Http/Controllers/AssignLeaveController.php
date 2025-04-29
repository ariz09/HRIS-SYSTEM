<?php
namespace App\Http\Controllers;

use App\Models\AssignLeave;
use App\Models\CDMLevel; // Use CDMLevel instead of Department
use Illuminate\Http\Request;

class AssignLeaveController extends Controller
{
    public function index()
    {
        $assignLeaves = AssignLeave::with('cdmLevel')->get(); // Change 'department' to 'cdmLevel'
        $cdmLevels = CDMLevel::all(); // Fetch all CDMLevels instead of Departments
        return view('assign_leaves.index', compact('assignLeaves', 'cdmLevels')); // Pass CDMLevels to the view
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cdm_level_id' => 'required|exists:cdm_levels,id', // Use 'cdm_level_id' instead of 'department_id'
            'leave_count' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        AssignLeave::create($validated);
        return redirect()->route('assign_leaves.index')
            ->with('success', 'Leave assigned successfully.');
    }

    public function update(Request $request, AssignLeave $assignLeave)
    {
        $validated = $request->validate([
            'cdm_level_id' => 'required|exists:cdm_levels,id', // Use 'cdm_level_id' instead of 'department_id'
            'leave_count' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $assignLeave->update($validated);
        return redirect()->route('assign_leaves.index')
            ->with('success', 'Leave assignment updated successfully');
    }

    public function destroy(AssignLeave $assignLeave)
    {
        $assignLeave->delete();
        return redirect()->route('assign_leaves.index')->with('success', 'Leave assignment deleted successfully');
    }
}
