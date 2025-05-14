<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->wantsJson()) {
            return response()->json(
                TimeRecord::where('user_id', $user->id)
                    ->whereDate('recorded_at', Carbon::today())
                    ->orderBy('recorded_at', 'desc')
                    ->get()
            );
        }

        return view('time_records.index'); // We'll create this Blade view
    }


    public function timeIn(Request $request)
{
    try {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Check if already timed in today
        if (TimeRecord::where('user_id', $user->id)
            ->whereDate('recorded_at', Carbon::today())
            ->where('type', 'time_in')
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already logged in today.'
            ], 409);
        }

        $timeRecord = TimeRecord::create([
            'user_id' => $user->id,
            'type' => 'time_in',
            'recorded_at' => now(),
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Time In recorded successfully.',
            'record' => $timeRecord
        ]);

    } catch (\Exception $e) {
        \Log::error('Time In Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server Error: ' . $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTrace() : null
        ], 500);
    }
}

    public function timeOut(Request $request)
    {
        try {
            $user = Auth::user();

            if (!TimeRecord::where('user_id', $user->id)
                ->whereDate('recorded_at', Carbon::today())
                ->where('type', 'time_in')
                ->exists()) {
                return redirect()->back()->with('error', 'You must Time In first before Timing Out.');
            }

            if (TimeRecord::where('user_id', $user->id)
                ->whereDate('recorded_at', Carbon::today())
                ->where('type', 'time_out')
                ->exists()) {
                return redirect()->back()->with('error', 'You have already logged out today.');
            }

            TimeRecord::create([
                'user_id' => $user->id,
                'type' => 'time_out',
                'recorded_at' => now(),
                'status' => 'pending',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->back()->with('success', 'Time Out recorded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error recording Time Out: ' . $e->getMessage());
        }
    }

    public function updateTimeInStatus(Request $request)
{
    $timeIn = TimeRecord::where('type', 'time_in')
                        ->where('status', 'pending')
                        ->latest()
                        ->first();

    if ($timeIn) {
        $timeIn->status = 'completed';
        $timeIn->save();

        return response()->json(['message' => 'Time-in status updated successfully.']);
    }

    return response()->json(['message' => 'No pending time-in record found.'], 400);
}

}


