<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TimeRecord;
use App\Models\Holiday; // If you have a Holiday model to save events
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get today's time records for the user
        $timeRecords = TimeRecord::where('user_id', $user->id)
            ->whereDate('recorded_at', Carbon::today())
            ->latest()
            ->get();

        // Get the latest time record
        $lastRecord = $timeRecords->first();

        // Initialize variables
        $canTimeIn = false;
        $canTimeOut = false;
        $statusMessage = '';

        // Check today's records
        if ($timeRecords->isEmpty()) {
            // No records today - can time in
            $canTimeIn = true;
            $statusMessage = 'You have not timed in today.';
        } else {
            // Check the last record
            if ($lastRecord->type === 'time_in') {
                $canTimeOut = true;
                $statusMessage = 'You have timed in at ' . $lastRecord->recorded_at->format('h:i A') . '.';
            } elseif ($lastRecord->type === 'time_out') {
                $statusMessage = 'You have completed your time in/out for today.';
            }
        }

        // Get holidays from the database
        $holidaysFromDb = Holiday::where('status', 'active')->get();

        // Map the database holidays into FullCalendar events
        $events = $holidaysFromDb->map(function ($holiday) {
            return [
                'title' => $holiday->name,
                'start' => Carbon::parse($holiday->date)->format('Y-m-d'),
            ];
        });

        return view('dashboard', compact('timeRecords', 'events', 'canTimeIn', 'canTimeOut', 'statusMessage'));
    }

    public function handleAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:time_in,time_out'
        ]);

        $user = Auth::user();

        // Get all records for today
        $todayRecords = TimeRecord::where('user_id', $user->id)
            ->whereDate('recorded_at', Carbon::today())
            ->orderBy('recorded_at', 'desc')
            ->get();

        // Check if user has already timed out today
        $hasTimedOut = $todayRecords->contains('type', 'time_out');

        // Get the last record for today
        $lastRecord = $todayRecords->first();

        // Validate the action
        if ($request->action === 'time_in') {
            if ($lastRecord && $lastRecord->type === 'time_in') {
                return response()->json([
                    'message' => 'You have already timed in today.',
                    'timeRecords' => $todayRecords
                ], 422);
            }
            if ($hasTimedOut) {
                return response()->json([
                    'message' => 'You have already timed out today.',
                    'timeRecords' => $todayRecords
                ], 422);
            }
        }

        if ($request->action === 'time_out') {
            if (!$lastRecord || $lastRecord->type === 'time_out') {
                return response()->json([
                    'message' => 'You need to time in first.',
                    'timeRecords' => $todayRecords
                ], 422);
            }
            if ($hasTimedOut) {
                return response()->json([
                    'message' => 'You have already timed out today.',
                    'timeRecords' => $todayRecords
                ], 422);
            }
        }

        // Create the time record
        TimeRecord::create([
            'user_id' => $user->id,
            'type' => $request->action,
            'recorded_at' => now(),
            'status' => 'active'
        ]);

        // Get updated records
        $timeRecords = TimeRecord::where('user_id', $user->id)
            ->whereDate('recorded_at', Carbon::today())
            ->latest()
            ->get();

        // Determine which button to show next
        $canTimeIn = $request->action === 'time_out';
        $canTimeOut = $request->action === 'time_in';

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $request->action)) . ' recorded successfully.',
            'timeRecords' => $timeRecords,
            'canTimeIn' => $canTimeIn,
            'canTimeOut' => $canTimeOut
        ]);
    }
}
