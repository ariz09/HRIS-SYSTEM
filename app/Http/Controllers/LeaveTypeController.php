<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import the Str class

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all(); // Fetch all leave types
        return view('leave_types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'status' => 'boolean'
        ]);

        // Automatically generate the code if not provided
        $validated['code'] = 'LT' . strtoupper(Str::random(4)); // Generate a random 4-character code

        LeaveType::create($validated);

        return redirect()->route('leave_types.index')
            ->with('success', 'Leave type created successfully.');
    }

    public function create()
    {
        return view('leave_types.index');
    }

    // Method to return data for the edit modal
    public function edit($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return response()->json($leaveType);
    }

    // Method to update the leave type
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'status' => 'boolean'
        ]);

        // If needed, generate the code for updates too (although this might not be necessary if code is fixed)
        $leaveType = LeaveType::findOrFail($id);
        
        // You can either generate the code or leave it unchanged, depending on your logic
        // Here we leave it unchanged, but you can re-assign it if needed
        $validated['code'] = $leaveType->code; 

        $leaveType->update($validated);

        return redirect()->route('leave_types.index')
            ->with('success', 'Leave type updated successfully.');
    }

    // Method to delete the leave type
    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $leaveType->delete();

        return redirect()->route('leave_types.index')
            ->with('success', 'Leave type deleted successfully.');
    }
}
