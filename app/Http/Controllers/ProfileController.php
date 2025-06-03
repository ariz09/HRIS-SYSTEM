<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use App\Models\PersonalInfo;
use App\Models\EmploymentInfo;
use App\Models\CompensationPackage;
use App\Models\EmployeeDependent;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeEducation;
use App\Models\EmployeeEmploymentHistory;
use App\Rules\StrongPassword;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employmentInfo = EmploymentInfo::with([
            'agency',
            'department',
            'cdmLevel',
            'position',
            'employmentType',
            'employmentStatus'
        ])->where('user_id', $user->id)->first();

        $employeeNumber = optional($employmentInfo)->employee_number;

        return view('profile.index', [
            'personalInfo' => PersonalInfo::where('user_id', $user->id)->first(),
            'employmentInfo' => $employmentInfo,
            'compensation' => $employeeNumber ? CompensationPackage::where('employee_number', $employeeNumber)->first() : null,
            'agency' => $employeeNumber ? EmploymentInfo::where('employee_number', $employeeNumber)->value('agency_id') : null,
            'dependents' => $employeeNumber ? EmployeeDependent::where('employee_number', $employeeNumber)->get() : collect(),
            'emergencyContacts' => $employeeNumber ? EmployeeEmergencyContact::where('employee_number', $employeeNumber)->get() : collect(),
            'education' => $employeeNumber ? EmployeeEducation::where('employee_number', $employeeNumber)->get() : collect(),
            'employmentHistory' => $employeeNumber ? EmployeeEmploymentHistory::where('employee_number', $employeeNumber)->get() : collect(),
            //'governmentIds' => $employeeNumber ? GovernmentId::where('employee_number', $employeeNumber)->first() : null,
        ]);
    }





        public function show()
        {
            $user = Auth::user();
            $employeeNumber = EmploymentInfo::where('userid', $user->id)->value('employee_number');

            return view('profile.index', [
                'personalInfo' => PersonalInfo::where('userid', $user->id)->first(),
                'employmentInfo' => EmploymentInfo::where('userid', $user->id)->first(),
                'compensation' => CompensationPackage::where('employee_number', $employeeNumber)->first(),
                'dependents' => EmployeeDependent::where('employee_number', $employeeNumber)->get(),
                'emergencyContacts' => EmployeeEmergencyContact::where('employee_number', $employeeNumber)->get(),
                'education' => EmployeeEducation::where('employee_number', $employeeNumber)->get(),
                'employmentHistory' => EmployeeEmploymentHistory::where('employee_number', $employeeNumber)->get(),
                //'governmentIds' => GovernmentId::where('employee_number', $employeeNumber)->first(),
            ]);
        }


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

    /**
     * Show the change password form.
     */
    public function changePassword(): View
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                new StrongPassword([
                    $request->user()->name,
                    $request->user()->email,
                    optional($request->user()->personalInfo)->birthday,
                ]),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
