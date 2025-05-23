<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Models\CompensationPackage;
use App\Models\EmploymentInfo;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // generate employee number
        $employee_number = EmployeeController::generateEmployeeNumber();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => false
        ]);

        PersonalInfo::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'preferred_name' => $request->name,
        ]);

        EmploymentInfo::create([
            'user_id' => $user->id,
            'employee_number' => $employee_number,
            'hiring_date' => now()->toDateString(),
        ]);

        CompensationPackage::create([
            'employee_number' => $employee_number
        ]);

        event(new Registered($user));

        $roleController = new RoleController();
        $roleController->assignDefaultRole($user);

        return redirect(route('login', absolute: false));
    }
}
