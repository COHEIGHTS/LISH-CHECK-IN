<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Auth::user()->leaves()->orderBy('created_at', 'desc')->get();
        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sick,vacation,personal,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leave.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show(Leave $leave)
    {
        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        return view('leave.show', compact('leave'));
    }

    public function destroy(Leave $leave)
    {
        if ($leave->user_id !== Auth::id() || $leave->status !== 'pending') {
            abort(403, 'Unauthorized access.');
        }

        $leave->delete();
        return redirect()->route('leave.index')->with('success', 'Leave request cancelled successfully.');
    }
}
