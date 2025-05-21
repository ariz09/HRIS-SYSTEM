<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use Illuminate\Http\Request;

class PersonalInfoController extends Controller
{
    /**
     * Display a listing of personal info.
     */
    public function index()
    {
        $personalInfos = PersonalInfo::all();

        return view('employees.personal_infos.index', compact('personalInfos'));
    }

    /**
     * Show the form for creating a new personal info.
     */
    public function create()
    {
        return view('employees.personal_infos.create');
    }

    /**
     * Store a newly created personal info in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['user_id', 'first_name', 'last_name', 'gender', 'address']);

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        PersonalInfo::create($data);

        return redirect()->route('personal_infos.index')
            ->with('success', 'Personal information added successfully.');
    }


    /**
     * Show the form for editing the specified personal info.
     */
    public function edit(PersonalInfo $personalInfo)
    {
        return view('employees.personal_infos.edit', compact('personalInfo'));
    }

    /**
     * Update the specified personal info in storage.
     */
    public function update(Request $request, PersonalInfo $personalInfo)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'gender' => 'required|in:Male,Female,Other',
        'address' => 'nullable|string|max:1000',
        'profile_picture' => 'nullable|image|max:2048',
    ]);

    $data = $request->only(['first_name', 'last_name', 'gender', 'address']);

    if ($request->hasFile('profile_picture')) {
        if ($personalInfo->profile_picture) {
            \Storage::disk('public')->delete($personalInfo->profile_picture);
        }

        $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
    }

    $personalInfo->update($data);

    return redirect()->route('personal_infos.index')
        ->with('success', 'Personal information updated successfully.');
}

    

    /**
     * Remove the specified personal info from storage.
     */
    public function destroy(PersonalInfo $personalInfo)
    {
        $personalInfo->delete();

        return redirect()->route('personal_infos.index')
            ->with('success', 'Personal information deleted successfully.');
    }
}
