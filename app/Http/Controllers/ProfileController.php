<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PersonalInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show()
    {
        $user = Auth::user();
        $personalInfo = PersonalInfo::where('user_id', $user->id)->first();

        return view('profile.show', [
            'user' => $user,
            'personalInfo' => $personalInfo
        ]);
    }

    /**
     * Update the user's personal information.
     */
    public function updatePersonal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'name_suffix' => 'nullable|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'birthday' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|in:Single,Married,Widowed,Separated,Divorced',
        ]);

        $user = Auth::user();

        // Create or update personal info
        $personalInfo = PersonalInfo::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return Redirect::route('profile.show')->with('status', 'personal-info-updated');
    }
}
