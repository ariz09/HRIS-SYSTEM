<?php

namespace App\Http\Controllers;

use App\Models\EmploymentType;
use Illuminate\Http\Request;

class EmploymentTypeController extends Controller
{
    public function index()
    {
        $employmentTypes = EmploymentType::all();
        return view('employment_types.index', compact('employmentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        EmploymentType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('employment_types.index')->with('success', 'Employment Type created successfully.');
    }

    public function edit(EmploymentType $employmentType)
    {
        return response()->json([
            'employmentType' => $employmentType
        ]);
    }

    public function update(Request $request, EmploymentType $employmentType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $employmentType->update([
            'name' => $request->name,
        ]);

        return redirect()->route('employment_types.index')->with('success', 'Employment Type updated successfully.');
    }

    public function destroy(EmploymentType $employmentType)
    {
        $employmentType->delete();

        return redirect()->route('employment_types.index')->with('success', 'Employment Type deleted successfully.');
    }
}
