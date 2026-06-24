<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $query = Leave::with('user')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Search by user name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $leaves = $query->paginate(10)->withQueryString();
        $pendingCount = Leave::where('status', 'pending')->count();

        return view('admin.leave-management', compact('leaves', 'pendingCount'));
    }

    public function show(Leave $leave)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $leave->load('user');
        return view('admin.leave-show', compact('leave'));
    }

    public function approve(Request $request, Leave $leave)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $leave->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.leave.index')->with('success', 'Leave request approved successfully.');
    }

    public function reject(Request $request, Leave $leave)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $leave->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.leave.index')->with('success', 'Leave request rejected successfully.');
    }

    public function destroy(Leave $leave)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $leave->delete();
        return redirect()->route('admin.leave.index')->with('success', 'Leave request deleted successfully.');
    }
}
