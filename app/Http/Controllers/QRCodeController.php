<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QRCodeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Generate or retrieve today's QR token
        $today = now()->format('Y-m-d');
        $qrToken = $this->getTodayQRToken($today);

        return view('qr.generate', compact('qrToken', 'today'));
    }

    private function getTodayQRToken($date)
    {
        // Check if a QR token already exists for today
        $existingAttendance = Attendance::where('attendance_date', $date)
            ->whereNotNull('qr_token')
            ->first();

        if ($existingAttendance) {
            return $existingAttendance->qr_token;
        }

        // Generate a new unique token for today
        return Str::random(32) . '-' . $date;
    }

    public function scan(Request $request)
    {
        // This will handle QR code scanning logic
        // For now, return a placeholder view
        return view('qr.scan');
    }

    public function verify(Request $request)
    {
        // This will verify QR code and mark attendance
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Check if user already has attendance for today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if ($existingAttendance) {
            return back()->with('error', 'You have already checked in today.');
        }

        // Verify QR token (simplified - in production, you'd verify against today's token)
        $todayToken = $this->getTodayQRToken($today);

        if ($request->qr_token !== $todayToken) {
            return back()->with('error', 'Invalid QR code.');
        }

        // Create attendance record
        $checkInTime = now();
        $status = 'present';

        // Check if late (after 9:00 AM for example)
        if ($checkInTime->hour >= 9 && $checkInTime->minute > 0) {
            $status = 'late';
        }

        Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $today,
            'check_in_time' => $checkInTime->format('H:i:s'),
            'status' => $status,
            'qr_token' => $request->qr_token,
        ]);

        return back()->with('success', 'Attendance marked successfully!');
    }
}
