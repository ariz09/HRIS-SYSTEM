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
            $employeeId = $user->employee->id ?? null;

            $timeRecords = collect();

            if ($employeeId) {
                $timeRecords = TimeRecord::with('employee')
                    ->where('employee_id', $employeeId)
                    ->whereDate('recorded_at', Carbon::today())
                    ->latest()
                    ->get();
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

            return view('dashboard', compact('timeRecords', 'events'));
        }



    public function handleAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:time_in,time_out,break_in,break_out'
        ]);

        $user = Auth::user();
        $employeeId = $user->employee->id ?? null;

        if (!$employeeId) {
            return response()->json([
                'message' => 'No linked employee record.',
                'timeRecords' => []
            ], 422);
        }

        TimeRecord::create([
            'employee_id' => $employeeId,
            'type' => $request->action,
            'recorded_at' => now(),
            'status' => 'pending'
        ]);

        $timeRecords = TimeRecord::with('employee')
            ->where('employee_id', $employeeId)
            ->whereDate('recorded_at', Carbon::today())
            ->latest()
            ->get();

        return response()->json([
            'message' => ucfirst(str_replace('_', ' ', $request->action)) . ' recorded successfully.',
            'timeRecords' => $timeRecords
        ]);
    }
}
