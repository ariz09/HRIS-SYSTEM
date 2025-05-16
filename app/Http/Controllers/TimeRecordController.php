<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
                'status' => 'on-going', // changed from 'pending' to 'on-going'
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

    public function myTimeRecords(Request $request)
    {
        $user = Auth::user();
        $query = \App\Models\TimeRecord::where('user_id', $user->id)
            ->with(['employee.department', 'employee.position', 'employee.agency']);

        if ($request->filled('start_date')) {
            $query->whereDate('recorded_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('recorded_at', '<=', $request->end_date);
        }
        $timeRecords = $query->orderBy('recorded_at', 'desc')->get();

        // Export to CSV if requested
        if ($request->has('report')) {
            $filename = 'my_time_records_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $columns = ['Date', 'Type', 'Time', 'Department', 'Position', 'Company', 'Status'];
            $callback = function() use ($timeRecords, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($timeRecords as $record) {
                    fputcsv($file, [
                        \Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d'),
                        ucfirst(str_replace('_', ' ', $record->type)),
                        \Carbon\Carbon::parse($record->recorded_at)->format('h:i:s A'),
                        optional($record->employee)->department->name ?? 'N/A',
                        optional($record->employee)->position->name ?? 'N/A',
                        optional($record->employee)->agency->name ?? 'N/A',
                        ucfirst($record->status),
                    ]);
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        }

        return view('time_records.my_time_records', compact('timeRecords'));
    }

    public function allTimeRecords(Request $request)
    {
        $query = \App\Models\TimeRecord::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'employee.agency'
        ]);

        if ($request->filled('start_date')) {
            $query->whereDate('recorded_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('recorded_at', '<=', $request->end_date);
        }
        $timeRecords = $query->orderBy('recorded_at', 'desc')->get();

        // Export to CSV if requested
        if ($request->has('report')) {
            $filename = 'all_time_records_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $columns = ['Employee', 'Date', 'Type', 'Time', 'Department', 'Position', 'Company', 'Status'];
            $callback = function() use ($timeRecords, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($timeRecords as $record) {
                    $employeeName = $record->employee->user->name ?? $record->employee->name ?? 'N/A';
                    fputcsv($file, [
                        $employeeName,
                        \Carbon\Carbon::parse($record->recorded_at)->format('Y-m-d'),
                        ucfirst(str_replace('_', ' ', $record->type)),
                        \Carbon\Carbon::parse($record->recorded_at)->format('h:i:s A'),
                        optional($record->employee)->department->name ?? 'N/A',
                        optional($record->employee)->position->name ?? 'N/A',
                        optional($record->employee)->agency->name ?? 'N/A',
                        ucfirst($record->status),
                    ]);
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        }

        return view('time_records.all_time_records', compact('timeRecords'));
    }
}


