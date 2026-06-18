<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class AdminApprovalController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Get paginated results for each status
        $pendingUsers = (clone $query)->where('status', 'pending')->latest()->paginate(10)->withQueryString();
        $approvedUsers = (clone $query)->where('status', 'approved')->latest()->paginate(10)->withQueryString();
        $rejectedUsers = (clone $query)->where('status', 'rejected')->latest()->paginate(10)->withQueryString();
        $suspendedUsers = (clone $query)->where('status', 'suspended')->latest()->paginate(10)->withQueryString();

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

    public function users(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $query = User::orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(7)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'approved')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'attachee' => User::where('role', 'attachee')->count(),
        ];

        return view('admin.users', compact('users', 'stats'));
    }

    public function deleteUser(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function attendance(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $query = User::where('role', '!=', 'admin')->orderBy('name');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->withQueryString();
        $attendanceData = [];

        foreach ($users as $user) {
            $attendances = Attendance::where('user_id', $user->id)->get();
            
            // Current month stats
            $currentMonth = now()->format('Y-m');
            $monthAttendances = $attendances->filter(function($att) use ($currentMonth) {
                return $att->attendance_date->format('Y-m') === $currentMonth;
            });

            // Current week stats
            $currentWeekStart = now()->startOfWeek();
            $currentWeekEnd = now()->endOfWeek();
            $weekAttendances = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
                return $att->attendance_date->between($currentWeekStart, $currentWeekEnd);
            });

            $attendanceData[$user->id] = [
                'user' => $user,
                'month' => [
                    'attended' => $monthAttendances->count(),
                    'late' => $monthAttendances->where('status', 'late')->count(),
                    'early' => $monthAttendances->filter(function($att) {
                        return $att->check_in_time && $att->check_in_time->format('H:i:s') <= '08:30:00';
                    })->count(),
                    'not_attended' => now()->daysInMonth - $monthAttendances->count(),
                ],
                'week' => [
                    'attended' => $weekAttendances->count(),
                    'late' => $weekAttendances->where('status', 'late')->count(),
                    'early' => $weekAttendances->filter(function($att) {
                        return $att->check_in_time && $att->check_in_time->format('H:i:s') <= '08:30:00';
                    })->count(),
                    'not_attended' => 7 - $weekAttendances->count(),
                ],
                'total' => [
                    'attended' => $attendances->count(),
                    'late' => $attendances->where('status', 'late')->count(),
                    'early' => $attendances->filter(function($att) {
                        return $att->check_in_time && $att->check_in_time->format('H:i:s') <= '08:30:00';
                    })->count(),
                ]
            ];
        }

        return view('admin.attendance', compact('attendanceData', 'users'));
    }

    public function reports(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        
        $query = Attendance::with('user');
        
        // Search by user name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('attendance_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('attendance_date', '<=', $request->date_to);
        }
        
        $attendances = $query->latest()->paginate(6)->withQueryString();

        // Analytics data
        $totalAttendance = Attendance::count();
        $totalPresent = Attendance::where('status', 'present')->count();
        $totalLate = Attendance::where('status', 'late')->count();
        
        // Calculate attendance rate based on present + late vs total
        $attendanceRate = $totalAttendance > 0 ? round((($totalPresent + $totalLate) / $totalAttendance) * 100, 2) : 0;
        
        $analytics = [
            'total_users' => $users->count(),
            'total_attendance_records' => $totalAttendance,
            'attendance_rate' => $attendanceRate,
            'late_rate' => $totalAttendance > 0 ? round(($totalLate / $totalAttendance) * 100, 2) : 0,
            'by_role' => [
                'staff' => [
                    'total' => $users->where('role', 'staff')->count(),
                    'attendance' => Attendance::whereHas('user', function($q) {
                        return $q->where('role', 'staff');
                    })->count()
                ],
                'attachee' => [
                    'total' => $users->where('role', 'attachee')->count(),
                    'attendance' => Attendance::whereHas('user', function($q) {
                        return $q->where('role', 'attachee');
                    })->count()
                ]
            ],
            'monthly_trend' => $this->getMonthlyTrend(Attendance::all()),
        ];

        return view('admin.reports', compact('analytics', 'users', 'attendances'));
    }

    private function getMonthlyTrend($attendances)
    {
        $trend = [];
        $previousCount = null;
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $monthName = now()->subMonths($i)->format('M Y');
            
            $monthAttendances = $attendances->filter(function($att) use ($month) {
                return $att->attendance_date->format('Y-m') === $month;
            });
            
            $count = $monthAttendances->count();
            $lateCount = $monthAttendances->where('status', 'late')->count();
            $lateRate = $count > 0 ? round(($lateCount / $count) * 100, 1) : 0;
            
            // Calculate month-over-month change
            $change = 0;
            $changePercent = 0;
            if ($previousCount !== null && $previousCount > 0) {
                $change = $count - $previousCount;
                $changePercent = round(($change / $previousCount) * 100, 1);
            }
            
            // Calculate unique users who attended this month
            $uniqueUsers = $monthAttendances->pluck('user_id')->unique()->count();
            
            $trend[] = [
                'month' => $monthName,
                'count' => $count,
                'late_count' => $lateCount,
                'late_rate' => $lateRate,
                'unique_users' => $uniqueUsers,
                'change' => $change,
                'change_percent' => $changePercent,
                'is_positive' => $change >= 0
            ];
            
            $previousCount = $count;
        }
        return $trend;
    }

    public function downloadPDF()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $attendances = Attendance::all();

        // Analytics data
        $totalAttendance = $attendances->count();
        $totalPresent = $attendances->where('status', 'present')->count();
        $totalLate = $attendances->where('status', 'late')->count();
        
        // Calculate attendance rate based on present + late vs total
        $attendanceRate = $totalAttendance > 0 ? round((($totalPresent + $totalLate) / $totalAttendance) * 100, 2) : 0;
        
        $analytics = [
            'total_users' => $users->count(),
            'total_attendance_records' => $totalAttendance,
            'attendance_rate' => $attendanceRate,
            'late_rate' => $totalAttendance > 0 ? round(($totalLate / $totalAttendance) * 100, 2) : 0,
            'by_role' => [
                'staff' => [
                    'total' => $users->where('role', 'staff')->count(),
                    'attendance' => $attendances->filter(function($att) {
                        return $att->user->role === 'staff';
                    })->count()
                ],
                'attachee' => [
                    'total' => $users->where('role', 'attachee')->count(),
                    'attendance' => $attendances->filter(function($att) {
                        return $att->user->role === 'attachee';
                    })->count()
                ]
            ],
            'monthly_trend' => $this->getMonthlyTrend($attendances),
        ];

        $pdf = PDF::loadView('admin.reports-pdf', compact('analytics', 'attendances'));
        
        return $pdf->download('lish-ai-attendance-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
