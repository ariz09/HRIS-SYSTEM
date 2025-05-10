<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewUserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => false,
            ]);

            // Get the employee role ID
            $employeeRoleId = DB::table('roles')
                ->where('name', 'employee')
                ->value('id');

            // Assign employee role to the new user
            if ($employeeRoleId) {
                DB::table('role_user')->insert([
                    'role_id' => $employeeRoleId,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Get all admin users and send them notification
            $adminRoleId = DB::table('roles')
                ->where('name', 'admin')
                ->value('id');

            if ($adminRoleId) {
                $adminUserIds = DB::table('role_user')
                    ->where('role_id', $adminRoleId)
                    ->pluck('user_id');

                $admins = User::whereIn('id', $adminUserIds)->get();

                Log::info('Found admin users:', ['count' => $admins->count()]);

                foreach ($admins as $admin) {
                    try {
                        $admin->notify(new NewUserRegistered($user));
                        Log::info('Notification sent to admin:', ['admin_id' => $admin->id]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send notification to admin:', [
                            'admin_id' => $admin->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            } else {
                Log::warning('Admin role not found in roles table');
            }

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in user registration:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
