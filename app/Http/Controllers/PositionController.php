<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\CDMLevel; // Ensure CDMLevel model is imported correctly
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 

class PositionController extends Controller
{
    public function index()
    {
        // Fetch positions with CDM Level relationship
        $positions = Position::with('cdmLevel')->latest()->get();
        
        // Fetch active CDM Levels
        $cdmLevels = CDMLevel::active()->get();  // Fetch active CDM levels

        // Return view with positions and CDM levels
        return view('positions.index', compact('positions', 'cdmLevels'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|max:100',
            'status' => 'required|boolean',
            'cdm_level_id' => 'required|exists:cdm_levels,id',
        ]);

        // Generate the position code
        $positionCode = 'POS' . strtoupper(Str::random(4));

        // Create the position
        $position = Position::create([
            'name' => $validated['name'],
            'status' => $validated['status'],
            'cdm_level_id' => $validated['cdm_level_id'],
            'code' => $positionCode, // Auto-generate code
        ]);

        return redirect()->route('positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function show($id)
    {
        // Fetch position with associated CDM Level
        $position = Position::with('cdmLevel')->findOrFail($id);
        return response()->json($position);
    }

    public function edit($id)
    {
        // Fetch position for editing
        $position = Position::findOrFail($id);
        return response()->json($position);
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        // Validate the updated form data, including CDM level
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:10|unique:positions,code,' . $position->id, // Allow the same code during update
            'name' => 'required|max:100',
            'status' => 'required|boolean',
            'cdm_level_id' => 'required|exists:cdm_levels,id' // Validate CDM level
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        // Update the position with validated data
        $position->update($request->only(['code', 'name', 'status', 'cdm_level_id']));

        return redirect()->route('positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')
            ->with('success', 'Position deleted successfully.');
    }
    public function getByCdmLevel($cdmLevel)
    {
        $positions = Position::where('cdm_level_id', $cdmLevel)->get();
        return response()->json($positions);
    }

}
