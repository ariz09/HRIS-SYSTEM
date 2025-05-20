<?php

namespace App\Http\Controllers;

use App\Models\File201;
use App\Models\EmploymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class File201Controller extends Controller
{
    public function index()
    {
        $files = File201::with('user')->get();
        
        // Get EmploymentInfo with PersonalInfo eager loaded via user_id
        $employees = EmploymentInfo::with(['personalInfo' => function($query) {
            $query->select('user_id', 'first_name', 'middle_name', 'last_name');
        }])
        ->orderBy('employee_number')
        ->get();
        
        return view('file201.index', compact('files', 'employees'));
    }
    // Add this to your File201Controller
    public function showAttachment($id)
    {
        $file = File201::findOrFail($id);
        
        // Add authorization check if needed
        // if (!auth()->user()->canViewFile($file)) { abort(403); }
        
        if ($file->attachment && Storage::disk('public')->exists($file->attachment)) {
            return response()->file(storage_path('app/public/' . $file->attachment));
        }
        
        abort(404);
    }
            
        

    public function store(Request $request)
    {
        $request->validate([
            'employee_number' => 'required|string',
            'file_type' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx',
        ]);

        $filename = null;

        if ($request->hasFile('attachment')) {
            $filename = $request->file('attachment')->store('201_files', 'public');
        }

        File201::create([
            'employee_number' => $request->employee_number,
            'file_type' => $request->file_type,
            'attachment' => $filename,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', '201 File uploaded successfully.');
    }

    public function destroy($id)
    {
        $file = File201::findOrFail($id);

        if ($file->attachment && Storage::disk('public')->exists($file->attachment)) {
            Storage::disk('public')->delete($file->attachment);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
