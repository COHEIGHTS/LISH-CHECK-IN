<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminApprovalController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $pendingUsers = User::where('status', 'pending')->latest()->get();
        $approvedUsers = User::where('status', 'approved')->latest()->get();
        $rejectedUsers = User::where('status', 'rejected')->latest()->get();
        $suspendedUsers = User::where('status', 'suspended')->latest()->get();

        $stats = [
            'total_users' => User::count(),
            'pending' => User::where('status', 'pending')->count(),
            'approved' => User::where('status', 'approved')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'attachee' => User::where('role', 'attachee')->count(),
        ];

        return view('admin.approval-dashboard', compact(
            'pendingUsers',
            'approvedUsers',
            'rejectedUsers',
            'suspendedUsers',
            'stats'
        ));
    }

    public function approve(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user->update(['status' => 'approved']);

        return back()->with('success', 'User has been approved successfully.');
    }

    public function reject(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user->update(['status' => 'rejected']);

        return back()->with('success', 'User has been rejected.');
    }

    public function suspend(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'User has been suspended.');
    }

    public function activate(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $user->update(['status' => 'approved']);

        return back()->with('success', 'User has been activated.');
    }
}
