<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInfo;
use Illuminate\Http\Request;

class EmployeeInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = EmployeeInfo::orderBy('last_name')->paginate(10);
        return view('employee-info.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee-info.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employment_infos,employee_number',
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'gender' => 'required|in:1,2,3'
        ]);

        EmployeeInfo::create($validated);

        return redirect()->route('employee-info.index')
                         ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeInfo $employeeInfo)
    {
        return view('employee-info.show', compact('employeeInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeInfo $employeeInfo)
    {
        return view('employee-info.edit', compact('employeeInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeInfo $employeeInfo)
    {
        $validated = $request->validate([
            'employee_number' => 'required|max:6|unique:employee_info,employee_number,'.$employeeInfo->id,
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'gender' => 'required|in:1,2,3'
        ]);

        $employeeInfo->update($validated);

        return redirect()->route('employee-info.index')
                         ->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeInfo $employeeInfo)
    {
        $employeeInfo->delete();

        return redirect()->route('employee-info.index')
                         ->with('success', 'Employee deleted successfully');
    }
}