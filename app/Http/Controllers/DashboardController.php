<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\TimeRecord;
use App\Models\PersonalInfo;
use App\Models\EmploymentInfo;
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

        public function getTodayTimeRecords()
        {
            $user = Auth::user();
            $employeeId = $user->employee->id ?? null;

            if (!$employeeId) {
                return response()->json([]);
            }

            $records = TimeRecord::where('employee_id', $employeeId)
                ->whereDate('recorded_at', today())
                ->get();

            return response()->json($records);
        }


        public function handleAction(Request $request)
        {
            $validated = $request->validate([
                'action' => 'required|in:time_in,time_out'
            ]);

            $user = Auth::user();
            $userId = $user->id;

            // Check personal and employment info
            $hasPersonalInfo = $user->personalInfo()->exists();
            $hasEmploymentInfo = $user->employmentInfo()->exists();

            if (!$hasPersonalInfo || !$hasEmploymentInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing personal or employment information. Please contact HR.'
                ], 422);
            }

            if ($request->action === 'time_out') {
                $existingOut = TimeRecord::where('user_id', $userId)
                    ->where('type', 'time_out')
                    ->whereDate('recorded_at', today())
                    ->first();

                if ($existingOut) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already timed out today.'
                    ]);
                }

                $timeInRecord = TimeRecord::where('user_id', $userId)
                    ->where('type', 'time_in')
                    ->whereDate('recorded_at', today())
                    ->first();

                if (!$timeInRecord) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must time in before timing out.'
                    ]);
                }

                try {
                    // Create time_out record
                    TimeRecord::create([
                        'user_id' => $userId,
                        'type' => 'time_out',
                        'recorded_at' => now(),
                        'status' => 'completed'
                    ]);

                    // Update the earlier time_in record status to completed (if not already)
                    if ($timeInRecord->status !== 'completed') {
                        $timeInRecord->status = 'completed';
                        $timeInRecord->save();
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Time-Out recorded successfully and Time-In marked as completed.'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'System error: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Handle time_in action
            if ($request->action === 'time_in') {
                $existingIn = TimeRecord::where('user_id', $userId)
                    ->where('type', 'time_in')
                    ->whereDate('recorded_at', today())
                    ->exists();

                if ($existingIn) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already timed in today.'
                    ]);
                }

                try {
                    TimeRecord::create([
                        'user_id' => $userId,
                        'type' => 'time_in',
                        'recorded_at' => now(),
                        'status' => 'on-going'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Time-In recorded successfully.'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'System error: ' . $e->getMessage()
                    ], 500);
                }
            }
        }


    }
