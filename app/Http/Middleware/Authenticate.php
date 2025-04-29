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
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                return $this->unauthenticated($request, $guard);
            }
        }

        return $next($request);
    }

    /**
     * Handle unauthenticated users.
     */
    protected function unauthenticated(Request $request, ?string $guard): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Redirect to appropriate login based on guard
        return match ($guard) {
            'admin' => redirect()->guest(route('admin.login')),
            default => redirect()->guest(route('login')),
        };
    }
}
