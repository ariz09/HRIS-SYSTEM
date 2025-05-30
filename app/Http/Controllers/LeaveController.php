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
        $leaves = Leave::with(['user', 'leaveType'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

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
        $user = Auth::user();
        $leaveTypes = LeaveType::all(); // get all leave types
        $employmentInfo = Auth::user()->employmentInfo; // get the employment info of the user
        $levelName = $employmentInfo && $employmentInfo->cdmLevel ? $employmentInfo->cdmLevel->name : null; // get the level name of the user
        $year = now()->year; // get the current year
        $userId = Auth::id(); // get the user id
        $balances = [];
        $entitlements = [];

        $position = $user->position->name ?? null;
        $cdmLevel = $user->position->cdmLevel->name ?? 'N/A';

        foreach ($leaveTypes as $type) {
            // Initialize balance if not present
            $balance = \App\Models\LeaveBalance::firstOrCreate([
                'user_id' => $userId,
                'leave_type_id' => $type->id,
                'year' => $year,
            ], [
                'balance' => optional(\App\Models\LeaveEntitlement::where('leave_type_id', $type->id)->where('cdm_level_id', $levelName)->first())->days_allowed ?? 0
            ]);
            $allowed = $balance->balance;
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
            // Add entitlement (days allowed for this leave type and level)
            $entitlement = \App\Models\LeaveEntitlement::where('leave_type_id', $type->id)
                ->where('cdm_level_id', $levelName)
                ->first();
            $entitlements[$type->id] = $entitlement ? $entitlement->days_allowed : 0;
        }
        return view('leaves.create', compact('leaveTypes', 'balances', 'entitlements', 'position', 'cdmLevel'));
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

        $employmentInfo = Auth::user()->employmentInfo;
        $levelName = $employmentInfo && $employmentInfo->cdmLevel ? $employmentInfo->cdmLevel->name : null;
        $leaveType = \App\Models\LeaveType::find($validated['leave_type_id']);
        $entitlementLevel = $levelName;
        if ($leaveType && $leaveType->name === 'Compassionate Leave') {
            $entitlementLevel = 'Parent/Spouse/Child';
        }
        $entitlement = \App\Models\LeaveEntitlement::where('leave_type_id', $validated['leave_type_id'])
            ->where('cdm_level_id', $entitlementLevel)
            ->first();
        if (!$entitlement) {
            return back()->withErrors(['You do not have an entitlement for this leave type/level.']);
        }
        $allowedDays = $entitlement->days_allowed;
        $start = new \Carbon\Carbon($validated['start_date']);
        $end = new \Carbon\Carbon($validated['end_date']);
        $requestedDays = $end->diffInDays($start) + 1;
        $year = now()->year;
        $userId = Auth::id();
        $leaveBalance = LeaveBalance::firstOrCreate([
            'user_id' => $userId,
            'leave_type_id' => $validated['leave_type_id'],
            'year' => $year,
        ], [
            'balance' => $allowedDays
        ]);
        $usedDays = \App\Models\Leave::where('user_id', $userId)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum(DB::raw('DATEDIFF(end_date, start_date) + 1'));
        if (($usedDays + $requestedDays) > $leaveBalance->balance) {
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
        // Deduct from balance only when approved (not here, but in approval logic)
        return redirect()->route('leaves.index')
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
}
