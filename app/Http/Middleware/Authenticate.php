<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? ['web'] : $guards;  // Default to 'web' guard

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                return $this->unauthenticated($request);
            }
        }

        return $next($request);
    }

    /**
     * Handle unauthenticated users.
     */
    protected function unauthenticated(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Redirect to the login route
        return redirect()->guest(route('login'));
    }
}
