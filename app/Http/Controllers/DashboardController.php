<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function staff()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Get all attendance records for the user
        $attendances = Attendance::where('user_id', $user->id)->orderBy('attendance_date', 'desc')->get();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        // Calculate stats
        $totalAttendanceDays = $attendances->count();
        $todayStatus = $todayAttendance ? ucfirst($todayAttendance->status) : 'Not Checked In';
        
        // Calculate attendance rate for current month
        $currentMonth = now()->format('Y-m');
        $monthAttendances = $attendances->filter(function($att) use ($currentMonth) {
            return $att->attendance_date->format('Y-m') === $currentMonth;
        });
        
        $daysInMonth = now()->daysInMonth;
        $attendanceRate = $daysInMonth > 0 ? round(($monthAttendances->count() / $daysInMonth) * 100, 1) : 0;

        // Weekly stats
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $weekAttendances = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
            return $att->attendance_date->between($currentWeekStart, $currentWeekEnd);
        });

        // Detailed summaries
        $summaries = [
            'daily' => [
                'present' => $monthAttendances->where('status', 'present')->count(),
                'late' => $monthAttendances->where('status', 'late')->count(),
                'total' => $monthAttendances->count(),
            ],
            'weekly' => [
                'present' => $weekAttendances->where('status', 'present')->count(),
                'late' => $weekAttendances->where('status', 'late')->count(),
                'total' => $weekAttendances->count(),
            ],
            'monthly' => [
                'present' => $monthAttendances->where('status', 'present')->count(),
                'late' => $monthAttendances->where('status', 'late')->count(),
                'total' => $monthAttendances->count(),
                'rate' => $attendanceRate,
            ],
        ];

        return view('dashboard.staff', compact(
            'totalAttendanceDays',
            'todayStatus',
            'attendanceRate',
            'todayAttendance',
            'summaries'
        ));
    }

    public function attachee()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Get all attendance records for the user
        $attendances = Attendance::where('user_id', $user->id)->orderBy('attendance_date', 'desc')->get();

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        // Calculate stats
        $totalAttendanceDays = $attendances->count();
        $todayStatus = $todayAttendance ? ucfirst($todayAttendance->status) : 'Not Checked In';
        
        // Calculate attendance rate for current month
        $currentMonth = now()->format('Y-m');
        $monthAttendances = $attendances->filter(function($att) use ($currentMonth) {
            return $att->attendance_date->format('Y-m') === $currentMonth;
        });
        
        $daysInMonth = now()->daysInMonth;
        $attendanceRate = $daysInMonth > 0 ? round(($monthAttendances->count() / $daysInMonth) * 100, 1) : 0;

        // Weekly stats
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $weekAttendances = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
            return $att->attendance_date->between($currentWeekStart, $currentWeekEnd);
        });

        // Detailed summaries
        $summaries = [
            'daily' => [
                'present' => $monthAttendances->where('status', 'present')->count(),
                'late' => $monthAttendances->where('status', 'late')->count(),
                'total' => $monthAttendances->count(),
            ],
            'weekly' => [
                'present' => $weekAttendances->where('status', 'present')->count(),
                'late' => $weekAttendances->where('status', 'late')->count(),
                'total' => $weekAttendances->count(),
            ],
            'monthly' => [
                'present' => $monthAttendances->where('status', 'present')->count(),
                'late' => $monthAttendances->where('status', 'late')->count(),
                'total' => $monthAttendances->count(),
                'rate' => $attendanceRate,
            ],
        ];

        return view('dashboard.attachee', compact(
            'totalAttendanceDays',
            'todayStatus',
            'attendanceRate',
            'todayAttendance',
            'summaries'
        ));
    }

    public function myAttendance()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        // Get recent attendance (last 7 days)
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->where('attendance_date', '>=', now()->subDays(7)->format('Y-m-d'))
            ->orderBy('attendance_date', 'desc')
            ->get();

        // Get this week's attendance
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $weekAttendances = Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_date', [$currentWeekStart, $currentWeekEnd])
            ->get();

        // Get this month's attendance
        $currentMonth = now()->format('Y-m');
        $monthAttendances = Attendance::where('user_id', $user->id)
            ->where('attendance_date', 'like', $currentMonth . '%')
            ->get();

        // Calculate stats
        $weekStats = [
            'present' => $weekAttendances->where('status', 'present')->count(),
            'late' => $weekAttendances->where('status', 'late')->count(),
            'total' => $weekAttendances->count(),
        ];

        $monthStats = [
            'present' => $monthAttendances->where('status', 'present')->count(),
            'late' => $monthAttendances->where('status', 'late')->count(),
            'total' => $monthAttendances->count(),
        ];

        return view('attendance.my-attendance', compact(
            'todayAttendance',
            'recentAttendances',
            'weekStats',
            'monthStats'
        ));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        
        // Get attendance records for the user with pagination and search
        $query = Attendance::where('user_id', $user->id)->orderBy('attendance_date', 'desc');

        // Search functionality - filter by date range or status
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('attendance_date', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(6)->withQueryString();

        // Get approved leave records for the user
        $leaves = \App\Models\Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('start_date', 'desc')
            ->get();

        // Calculate statistics
        $totalAttendances = Attendance::where('user_id', $user->id)->count();
        $presentCount = Attendance::where('user_id', $user->id)->where('status', 'present')->count();
        $lateCount = Attendance::where('user_id', $user->id)->where('status', 'late')->count();
        
        // Calculate total leave days
        $totalLeaveDays = $leaves->sum(function($leave) {
            return (int)$leave->start_date->diffInDays($leave->end_date) + 1;
        });
        
        // Current month stats
        $currentMonth = now()->format('Y-m');
        $monthAttendances = Attendance::where('user_id', $user->id)
            ->where('attendance_date', 'like', $currentMonth . '%')
            ->get();
            
        // Calculate leave days for current month
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $monthLeaveDays = 0;
        foreach ($leaves as $leave) {
            $leaveStart = max($leave->start_date, $currentMonthStart);
            $leaveEnd = min($leave->end_date, $currentMonthEnd);
            if ($leaveStart <= $leaveEnd) {
                $monthLeaveDays += (int)$leaveStart->diffInDays($leaveEnd) + 1;
            }
        }

        return view('attendance.history', compact(
            'attendances',
            'leaves',
            'totalAttendances',
            'presentCount',
            'lateCount',
            'totalLeaveDays',
            'monthAttendances',
            'monthLeaveDays'
        ));
    }
}
