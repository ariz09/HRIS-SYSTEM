<?php
// app/Http/Controllers/AgencyController.php
namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::all();
        return view('agencies.index', compact('agencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        // Auto-generate the agency code
        $validated['code'] = 'AG' . strtoupper(Str::random(4)); // Prefix 'AG' and generate 4 random characters

        // Create the agency with the generated code
        Agency::create($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agency created successfully');
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean'
        ]);

        // If you want to allow updating of the code, uncomment the line below:
        // $validated['code'] = 'AG' . strtoupper(Str::random(4));

        $agency->update($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agency updated successfully');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Agency deleted successfully');
    }
}
