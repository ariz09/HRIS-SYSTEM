<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    // Show all holidays
    public function index()
    {
        $holidays = Holiday::all(); // Get all holidays from the database
        return view('holidays.index', compact('holidays')); // Pass holidays to the Blade view
    }

    // Store a new holiday
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:regular,special', // Add validation for type
        ]);

        $holiday = Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'status' => $request->status,
            'type' => $request->type, // Save the type
        ]);

        // Check if the request is for a web or API response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Holiday added successfully.',
                'holiday' => $holiday
            ], 201); // Return JSON with success message and the created holiday
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('holidays.index')
            ->with('success', 'Holiday added successfully.');
    }

    // Update an existing holiday
    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:regular,special', // Add validation for type
        ]);

        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'status' => $request->status,
            'type' => $request->type, // Update the type
        ]);

        // Check if the request is for a web or API response
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Holiday updated successfully.',
                'holiday' => $holiday
            ]);
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    // Delete a holiday
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        // Check if the request is for a web or API response
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Holiday deleted successfully']);
        }

        // Flash success message and redirect to index for web users
        return redirect()->route('holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
}
