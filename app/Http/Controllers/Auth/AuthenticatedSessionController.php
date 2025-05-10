<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Check if user has employee role
            if ($user && $user->roles()->where('name', 'employee')->exists()) {
                return redirect()->intended(route('employee.dashboard', absolute: false));
            }

            // Check if user is admin and redirect accordingly
            if (session('is_admin')) {
                session()->forget('is_admin'); // Clear the session flag
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            // Default redirect for other roles
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if the error message is 'pending'
            if ($e->getMessage() === 'pending') {
                // Get the pending user from session
                $pendingUser = session('pending_user');
                // Clear the session
                session()->forget('pending_user');
                // Redirect to pending page
                return redirect()->route('pending');
            }
            // If it's any other validation error, throw it
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
