<?php

namespace App\Http\Controllers;

use App\Models\CutOffType;
use Illuminate\Http\Request;

class CutOffTypeController extends Controller
{
    public function index()
    {
        $cutOffTypes = CutOffType::all();
        return view('cut_off_types.index', compact('cutOffTypes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        CutOffType::create($request->all());

        return redirect()->route('cut_off_types.index')->with('success', 'Cut-off type added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $cutOffType = CutOffType::findOrFail($id);
        $cutOffType->update($request->all());

        return redirect()->route('cut_off_types.index')->with('success', 'Cut-off type updated successfully.');
    }

    public function destroy($id)
    {
        CutOffType::destroy($id);
        return redirect()->route('cut_off_types.index')->with('success', 'Cut-off type deleted successfully.');
    }
}
