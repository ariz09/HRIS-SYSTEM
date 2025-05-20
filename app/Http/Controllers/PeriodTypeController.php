<?php

namespace App\Http\Controllers;

use App\Models\PeriodType;
use Illuminate\Http\Request;

class PeriodTypeController extends Controller
{
    public function index()
    {
        $periodTypes = PeriodType::all();
        return view('period_types.index', compact('periodTypes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        PeriodType::create($request->only('name'));
        return redirect()->route('period_types.index')->with('success', 'Period type added successfully.');
    }

    public function edit($id)
    {
        $periodType = PeriodType::findOrFail($id);
        return response()->json(['periodType' => $periodType]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $periodType = PeriodType::findOrFail($id);
        $periodType->update($request->only('name'));
        return redirect()->route('period_types.index')->with('success', 'Period type updated successfully.');
    }

    public function destroy($id)
    {
        $periodType = PeriodType::findOrFail($id);
        $periodType->delete();
        return redirect()->route('period_types.index')->with('success', 'Period type deleted successfully.');
    }
}
