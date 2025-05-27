<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('leaveTypes'));
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

        $leave = new Leave();
        $leave->user_id = Auth::id();
        $leave->leave_type_id = $validated['leave_type_id'];
        $leave->duration = $validated['duration'];
        $leave->start_date = $validated['start_date'];
        $leave->end_date = $validated['end_date'];
        $leave->reason = $validated['reason'];
        $leave->contact_number = $validated['contact_number'];
        $leave->address_during_leave = $validated['address_during_leave'];
        $leave->status = 'pending';
        $leave->save();

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
