<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user has completed their profile
        $hasDependents = $user->employmentInfo?->dependents()->exists();
        $hasEducation = $user->employmentInfo?->educations()->exists();
        $hasEmergencyContact = $user->employmentInfo?->emergencyContact()->exists();
        $hasEmploymentHistory = $user->employmentInfo?->employmentHistories()->exists();
        $hasEmploymentInfo = $user->employmentInfo()->exists();
        $hasEmploymentStatus = $user->employmentInfo?->employmentStatus()->exists();

        // If any required information is missing, redirect to profile completion
        if (!$hasDependents || !$hasEducation || !$hasEmergencyContact ||
            !$hasEmploymentHistory || !$hasEmploymentInfo || !$hasEmploymentStatus) {

            // Store the intended URL to redirect back after completion
            if ($request->route()->getName() !== 'profile.complete') {
                session()->put('url.intended', $request->url());
            }

            return redirect()->route('profile.complete');
        }

        return $next($request);
    }
}
