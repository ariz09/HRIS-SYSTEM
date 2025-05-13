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
        // For API requests (from JavaScript)
        if ($request->wantsJson()) {
            return response()->json(
                TimeRecord::with('user')
                    ->whereDate('recorded_at', Carbon::today())
                    ->orderBy('recorded_at', 'desc')
                    ->get()
            );
        }

        // For regular page loads
        return TimeRecord::with('user')
            ->whereDate('recorded_at', Carbon::today())
            ->orderBy('recorded_at', 'desc')
            ->get();
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
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if timed in first
            $timeInRecord = TimeRecord::where('user_id', $user->id)
                ->whereDate('recorded_at', Carbon::today())
                ->where('type', 'time_in')
                ->first();

            if (!$timeInRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must Time In first before Timing Out.'
                ], 400);
            }

            // Check if already timed out
            if (TimeRecord::where('user_id', $user->id)
                ->whereDate('recorded_at', Carbon::today())
                ->where('type', 'time_out')
                ->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already logged out today.'
                ], 409);
            }

            // Update the time-in record's status to completed
            $timeInRecord->update(['status' => 'completed']);

            // Create the time-out record
            $timeOutRecord = TimeRecord::create([
                'user_id' => $user->id,
                'type' => 'time_out',
                'recorded_at' => now(),
                'status' => 'pending',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time Out recorded successfully, and Time In status updated.',
                'record' => $timeOutRecord
            ]);

        } catch (\Exception $e) {
            \Log::error('Time Out Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }
}
