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
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $data = $request->only('name', 'status');
    
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('agency_logos', 'public');
        }
    
        Agency::create($data);
    
        return redirect()->route('agencies.index')->with('success', 'Agency created successfully.');
    }
    
    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $data = $request->only('name', 'status');
    
        if ($request->hasFile('logo')) {
            // Optionally delete old image
            if ($agency->logo && Storage::disk('public')->exists($agency->logo)) {
                Storage::disk('public')->delete($agency->logo);
            }
    
            $data['logo'] = $request->file('logo')->store('agency_logos', 'public');
        }
    
        $agency->update($data);
    
        return redirect()->route('agencies.index')->with('success', 'Agency updated successfully.');
    }
        public function destroy(Agency $agency)
    {
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Agency deleted successfully');
    }
}
