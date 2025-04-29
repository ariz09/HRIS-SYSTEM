<?php

namespace App\Http\Controllers;

use App\Models\CDMLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CDMLevelController extends Controller
{
    public function index()
    {
        $cdmLevels = CDMLevel::all();
        return view('cdmlevels.index', compact('cdmLevels'));
    }

    public function create()
    {
        return view('cdmlevels.create');
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Generate code here on the server side, ensuring uniqueness
        $code = 'CDM' . strtoupper(Str::random(4)); // Generates a random 4-character code
        $request->merge(['code' => $code]); // Merge the code into the request data

        // Create the CDM Level
        CDMLevel::create($request->all());

        return redirect()->route('cdmlevels.index')->with('success', 'CDM Level created successfully.');
    }

    public function edit($id)
    {
        $cdmLevel = CDMLevel::find($id); // Fetch the CDM level by its ID

        if (!$cdmLevel) {
            return response()->json(['error' => 'CDM Level not found'], 404); // Return error if not found
        }

        return response()->json($cdmLevel); // Return the CDM level data in JSON format
    }

    public function update(Request $request, $id)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the CDM level by ID
        $cdmLevel = CDMLevel::find($id);

        if (!$cdmLevel) {
            return redirect()->route('cdmlevels.index')->with('error', 'CDM Level not found.');
        }

        // Update the CDM level
        $cdmLevel->name = $request->input('name');
        $cdmLevel->save();

        return redirect()->route('cdmlevels.index')->with('success', 'CDM Level updated successfully.');
    }

    public function destroy($id)
    {
        $cdmLevel = CDMLevel::findOrFail($id);

        if ($cdmLevel->positions()->exists() || $cdmLevel->employees()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this CDM Level because it is associated with positions or employees.');
        }

        $cdmLevel->delete();

        return redirect()->route('cdmlevels.index')->with('success', 'CDM Level deleted successfully.');
    }
}
