<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QRCodeController extends Controller
{
    public function index(Request $request)
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
        // Use cache to store/retrieve the daily QR token
        $cacheKey = 'daily_qr_token_' . $date;
        
        $token = cache()->get($cacheKey);
        
        if ($token) {
            return $token;
        }

        // Generate a new unique token for today and store it in cache
        $token = Str::random(32) . '-' . $date;
        cache()->put($cacheKey, $token, now()->endOfDay());
        
        return $token;
    }

    public function scan(Request $request)
    {
        // Handle QR code scanning logic
        return view('qr.scan');
    }

    public function verify(Request $request)
    {
        // Validate request
        $request->validate([
            'qr_token' => 'required|string',
        ], [
            'qr_token.required' => 'Please enter the QR token.',
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

        // Get today's valid QR token
        $todayToken = $this->getTodayQRToken($today);

        // Verify QR token
        if ($request->qr_token !== $todayToken) {
            return back()->with('error', 'Invalid QR token. Please check with your admin.');
        }

        // Create attendance record
        $checkInTime = now();
        $status = 'present';

        // Check if late (after 9:00 AM)
        $lateThreshold = $checkInTime->copy()->setHour(9)->setMinute(0)->setSecond(0);
        if ($checkInTime->gt($lateThreshold)) {
            $status = 'late';
        }

        try {
            Attendance::create([
                'user_id' => $user->id,
                'attendance_date' => $today,
                'check_in_time' => $checkInTime->format('H:i:s'),
                'status' => $status,
                'qr_token' => $request->qr_token,
            ]);

            return back()->with('success', 'Attendance marked successfully! Check-in time: ' . substr($checkInTime->format('H:i:s'), 0, 5));
        } catch (\Exception $e) {
            return back()->with('error', 'Error marking attendance. Please try again.');
        }
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Check if user has attendance for today
        $attendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No check-in record found for today. Please check in first.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'You have already checked out today.');
        }

        try {
            $checkOutTime = now();
            $attendance->update([
                'check_out_time' => $checkOutTime->format('H:i:s'),
            ]);

            $hoursWorked = $attendance->hours_worked;
            return back()->with('success', 'Check-out successful! Time: ' . substr($checkOutTime->format('H:i:s'), 0, 5) . ' | Hours worked: ' . $hoursWorked);
        } catch (\Exception $e) {
            return back()->with('error', 'Error checking out. Please try again.');
        }
    }
}
