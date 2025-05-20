<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Show all departments
    public function index()
    {
        $departments = Department::all(); // Get all departments from the database
        return view('departments.index', compact('departments')); // Pass departments to the Blade view
    }

    // Store a new department
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,0', // 1 for active, 0 for inactive
        ]);

        // Automatically generate unique department code
        $departmentCode = $this->generateDepartmentCode();

        $department = Department::create([
            'name' => $request->name,
            'code' => $departmentCode,  // Store generated code
            'status' => $request->status,
        ]);

        // Check if the request is for a web or API response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Department added successfully.',
                'department' => $department
            ], 201); // Return JSON with success message and the created department
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('departments.index')
            ->with('success', 'Department added successfully.');
    }

    // DepartmentController.php

public function edit($id)
{
    $department = Department::findOrFail($id);
    return response()->json(['department' => $department]);
}




    // Update an existing department
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:1,0', // 1 for active, 0 for inactive
        ]);

        $department->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Check if the request is for a web or API response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Department updated successfully.',
                'department' => $department
            ]);
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    // Delete a department
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        // Check if the request is for a web or API response
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Department deleted successfully']);
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    // Generate unique department code
    private function generateDepartmentCode()
    {
        $prefix = 'DEPT';
        $randomCode = strtoupper(uniqid()); // Unique ID with prefix
        return $prefix . $randomCode;
    }
}
