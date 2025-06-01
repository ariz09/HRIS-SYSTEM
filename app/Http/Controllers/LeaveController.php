<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LeaveBalance;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasAnyRole(['admin', 'manager', 'supervisor'])) {
            $leaves = Leave::with(['user', 'leaveType'])
                ->latest()
                ->get();
        } else {
            $leaves = Leave::with(['user', 'leaveType'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Display the authenticated user's leave requests.
     */
    public function myLeaves()
    {
        $leaves = Leave::with(['leaveType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('leaves.my', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user(); // Get the authenticated user
        $leaveTypes = LeaveType::all(); // get all leave types
        $employmentInfo = Auth::user()->employmentInfo; // get the employment info of the user
        $levelName = $employmentInfo && $employmentInfo->cdmLevel ? $employmentInfo->cdmLevel->name : null; // get the level name of the user
        $year = now()->year; // get the current year
        $userId = Auth::id(); // get the user id
        $balances = [];
        $entitlements = [];

        $position = $user->position->name ?? null;
        $cdmLevel = $user->position->cdmLevel->name ?? 'N/A';

        // Get all leave entitlements for a specific level
        $entitlementModels = \App\Models\LeaveEntitlement::where('cdm_level_id', $user->position->cdmLevel->id)
            ->with('leaveType')
            ->get();

        $leaveDays = [];
        foreach ($entitlementModels as $entitlement) {
            $leaveDays[$entitlement->leaveType->name] = $entitlement->days_allowed;
        }

        foreach ($entitlementModels as $entitlement) {
            $leaveDaysMap[$entitlement->leave_type_id] = $entitlement->days_allowed;
        }

        $balances = [];
        $entitlements = [];

        foreach ($leaveTypes as $type) {
            $daysAllowed = $leaveDaysMap[$type->id] ?? 0;

            // Initialize or update leave balance
            $balance = \App\Models\LeaveBalance::firstOrCreate(
                [
                    'user_id' => $userId,
                    'leave_type_id' => $type->id,
                    'year' => $year
                ],
                [
                    'balance' => $daysAllowed
                ]
            );

            $allowed = $balance->balance;

            // Calculate used leave days
            $used = \App\Models\Leave::where('user_id', $userId)
                ->where('leave_type_id', $type->id)
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));

            $balances[$type->id] = [
                'allowed' => $allowed,
                'used' => $used,
                'remaining' => max($allowed - $used, 0),
            ];

            // Store entitlement for reference
            $entitlements[$type->id] = $daysAllowed;
        }
        return view('leaves.create', compact('leaveTypes', 'balances', 'entitlements', 'position', 'cdmLevel', 'entitlementModels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'duration' => 'required|in:full_day,half_day_morning,half_day_afternoon',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'contact_number' => 'required|string',
            'address_during_leave' => 'required|string',
        ]);

        $userId = Auth::id();
        $year = now()->year;

        $employmentInfo = Auth::user()->employmentInfo;
        $cdmLevelId = $employmentInfo->position?->cdm_level_id;
        $leaveType = \App\Models\LeaveType::find($validated['leave_type_id']);

        $entitlementLevel = $cdmLevelId;
        if ($leaveType && $leaveType->name === 'Compassionate Leave') {
            $entitlementLevel = 'Parent/Spouse/Child';
        }
        $entitlement = \App\Models\LeaveEntitlement::where('leave_type_id', $validated['leave_type_id'])
            ->where('cdm_level_id', $cdmLevelId)
            ->first();

        if (!$entitlement) {
            return back()->withErrors(['You do not have an entitlement for this leave type/level.']);
        }
        $allowedDays = $entitlement->days_allowed;
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);

        $from = $start->lessThan($end) ? $start : $end;
        $to = $start->greaterThan($end) ? $start : $end;

        $requestedDays = $from->diffInDays($to) + 1;

        // Calculate actual days based on duration
        if ($validated['duration'] === 'half_day_morning' || $validated['duration'] === 'half_day_afternoon') {
            $requestedDays = $requestedDays * 0.5;
        }

        $leaveBalance = LeaveBalance::where('user_id', $userId)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('year', $year)
            ->first();

        if (!$leaveBalance || $leaveBalance->balance < $requestedDays) {
            return back()->withErrors(['You have exceeded your leave balance for this type.']);
        }
        $leave = new Leave();
        $leave->user_id = $userId;
        $leave->leave_type_id = $validated['leave_type_id'];
        $leave->duration = $validated['duration'];
        $leave->start_date = $validated['start_date'];
        $leave->end_date = $validated['end_date'];
        $leave->reason = $validated['reason'];
        $leave->contact_number = $validated['contact_number'];
        $leave->address_during_leave = $validated['address_during_leave'];
        $leave->status = 'pending';
        $leave->save();

        return redirect()->route('leaves.my')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = Leave::with(['user', 'leaveType'])
            ->findOrFail($id);

        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $leave = Leave::findOrFail($id);
        $leaveTypes = LeaveType::all();

        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leave = Leave::findOrFail($id);

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'duration' => 'required|in:full_day,half_day_morning,half_day_afternoon',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
            'contact_number' => 'required|string',
            'address_during_leave' => 'required|string',
        ]);

        $leave->update($validated);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request cancelled successfully.');
    }

    /**
     * Approve a leave request and deduct from leave balance.
     */
    public function approve($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);

        if ($leave->status !== 'approved') {
            // Calculate the number of days
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            $days = $startDate->diffInDays($endDate) + 1;

            // Adjust for half-day leaves
            if ($leave->duration !== 'full_day') {
                $days = $days * 0.5;
            }

            // Get the year of the leave request
            $year = $startDate->year;

            // Find the leave balance
            $leaveBalance = LeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', $year)
                ->first();

            if (!$leaveBalance) {
                return back()->withErrors(['Leave balance not found for this period.']);
            }

            // Check if there's enough balance
            if ($leaveBalance->balance < $days) {
                return back()->withErrors(['Insufficient leave balance.']);
            }

            // Calculate new balance
            $currentBalance = (float)$leaveBalance->balance;
            $daysToDeduct = (float)$days;
            $newBalance = $currentBalance - $daysToDeduct;

            // Update the leave balance
            $leaveBalance->balance = $newBalance;
            $leaveBalance->save();

            // Verify the update
            $updatedBalance = LeaveBalance::where('id', $leaveBalance->id)->first();

            if ($updatedBalance->balance != $newBalance) {
                // If the balance wasn't updated correctly, try to force it
                DB::table('leave_balances')
                    ->where('id', $leaveBalance->id)
                    ->update(['balance' => $newBalance]);
            }

            // Update leave status
            $leave->status = 'approved';
            $leave->save();

            // Log the final state
            \Log::info('Leave Balance Update', [
                'leave_id' => $leave->id,
                'user_id' => $leave->user_id,
                'leave_type_id' => $leave->leave_type_id,
                'year' => $year,
                'original_balance' => $currentBalance,
                'days_deducted' => $daysToDeduct,
                'expected_new_balance' => $newBalance,
                'actual_new_balance' => $updatedBalance->balance
            ]);
        }

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request has been approved and balance updated.');
    }

    /**
     * Display all leave requests for management.
     */
    public function manage(Request $request)
    {
        $query = Leave::with(['user', 'leaveType'])
            ->latest();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $leaves = $query->get();

        return view('leaves.manage', compact('leaves'));
    }

    /**
     * Reject a leave request.
     */
    public function reject($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        if ($leave->status === 'pending') {
            $leave->status = 'rejected';
            $leave->save();
        }

        return redirect()->route('leaves.manage')
            ->with('success', 'Leave request has been rejected.');
    }
}
