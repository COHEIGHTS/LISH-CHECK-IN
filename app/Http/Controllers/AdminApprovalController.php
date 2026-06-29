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

        // Get all users for summary calculation (not just paginated)
        $allUsersQuery = User::where('role', '!=', 'admin')->orderBy('name');
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $allUsersQuery->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($request->has('role') && $request->role != '') {
            $allUsersQuery->where('role', $request->role);
        }
        $allUsers = $allUsersQuery->get();

        // Initialize summary counters
        $summary = [
            'week' => ['attended' => 0, 'late' => 0, 'early' => 0, 'not_attended' => 0, 'hours_worked' => 0],
            'month' => ['attended' => 0, 'late' => 0, 'early' => 0, 'not_attended' => 0, 'hours_worked' => 0],
            'total' => ['attended' => 0, 'late' => 0, 'early' => 0, 'hours_worked' => 0]
        ];

        // Calculate summary from all users
        foreach ($allUsers as $user) {
            $attendances = Attendance::where('user_id', $user->id)->get();
            
            // Current month stats
            $currentMonthStart = now()->startOfMonth();
            $currentMonthEnd = now()->endOfMonth();
            $monthAttendances = $attendances->filter(function($att) use ($currentMonthStart, $currentMonthEnd) {
                return $att->attendance_date >= $currentMonthStart && $att->attendance_date <= $currentMonthEnd;
            });

            // Current week stats (Monday to Sunday)
            $currentWeekStart = now()->startOfWeek()->startOfDay();
            $currentWeekEnd = now()->endOfWeek()->endOfDay();
            $weekAttendances = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
                return $att->attendance_date->format('Y-m-d') >= $currentWeekStart->format('Y-m-d') && $att->attendance_date->format('Y-m-d') <= $currentWeekEnd->format('Y-m-d');
            });

            // Count attended (present + late)
            $monthAttended = $monthAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();
            
            $weekAttended = $weekAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();

            $totalAttended = $attendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();

            // Accumulate summary data
            $summary['week']['attended'] += $weekAttended;
            $summary['week']['late'] += $weekAttendances->where('status', 'late')->count();
            $summary['week']['early'] += $weekAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
            })->count();
            $summary['week']['not_attended'] += (7 - $weekAttendances->count());
            $summary['week']['hours_worked'] += $weekAttendances->sum(function($att) {
                return $att->hours_worked;
            });

            $summary['month']['attended'] += $monthAttended;
            $summary['month']['late'] += $monthAttendances->where('status', 'late')->count();
            $summary['month']['early'] += $monthAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
            })->count();
            $summary['month']['not_attended'] += (now()->daysInMonth - $monthAttendances->count());
            $summary['month']['hours_worked'] += $monthAttendances->sum(function($att) {
                return $att->hours_worked;
            });

            $summary['total']['attended'] += $totalAttended;
            $summary['total']['late'] += $attendances->where('status', 'late')->count();
            $summary['total']['early'] += $attendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
            })->count();
            $summary['total']['hours_worked'] += $attendances->sum(function($att) {
                return $att->hours_worked;
            });
        }

        // Now calculate individual user data for paginated users
        foreach ($users as $user) {
            $attendances = Attendance::where('user_id', $user->id)->get();
            
            // Current month stats
            $currentMonthStart = now()->startOfMonth();
            $currentMonthEnd = now()->endOfMonth();
            $monthAttendances = $attendances->filter(function($att) use ($currentMonthStart, $currentMonthEnd) {
                return $att->attendance_date >= $currentMonthStart && $att->attendance_date <= $currentMonthEnd;
            });

            // Current week stats (Monday to Sunday)
            $currentWeekStart = now()->startOfWeek()->startOfDay();
            $currentWeekEnd = now()->endOfWeek()->endOfDay();
            $weekAttendances = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
                return $att->attendance_date->format('Y-m-d') >= $currentWeekStart->format('Y-m-d') && $att->attendance_date->format('Y-m-d') <= $currentWeekEnd->format('Y-m-d');
            });

            // Count attended (present + late)
            $monthAttended = $monthAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();
            
            $weekAttended = $weekAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();

            $totalAttended = $attendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();

            $attendanceData[$user->id] = [
                'user' => $user,
                'month' => [
                    'attended' => $monthAttended,
                    'late' => $monthAttendances->where('status', 'late')->count(),
                    'early' => $monthAttendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'not_attended' => now()->daysInMonth - $monthAttendances->count(),
                    'hours_worked' => $monthAttendances->sum(function($att) {
                        return $att->hours_worked;
                    }),
                ],
                'week' => [
                    'attended' => $weekAttended,
                    'late' => $weekAttendances->where('status', 'late')->count(),
                    'early' => $weekAttendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'not_attended' => 7 - $weekAttendances->count(),
                    'hours_worked' => $weekAttendances->sum(function($att) {
                        return $att->hours_worked;
                    }),
                ],
                'total' => [
                    'attended' => $totalAttended,
                    'late' => $attendances->where('status', 'late')->count(),
                    'early' => $attendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'hours_worked' => $attendances->sum(function($att) {
                        return $att->hours_worked;
                    }),
                ]
            ];
        }

        return view('admin.attendance', compact('attendanceData', 'users', 'summary'));
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
        
        // Calculate total hours worked using model accessor
        $allAttendances = Attendance::all();
        $totalHoursWorked = $allAttendances->sum('hours_worked');
        
        // Calculate current month hours worked
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $monthAttendances = Attendance::whereBetween('attendance_date', [$currentMonthStart, $currentMonthEnd])->get();
        $monthHoursWorked = $monthAttendances->sum('hours_worked');
        
        // Calculate current week hours worked
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $weekAttendances = Attendance::whereBetween('attendance_date', [$currentWeekStart, $currentWeekEnd])->get();
        $weekHoursWorked = $weekAttendances->sum('hours_worked');
        
        // Leave analytics
        $allLeaves = \App\Models\Leave::all();
        $totalLeaves = $allLeaves->count();
        $approvedLeaves = $allLeaves->where('status', 'approved')->count();
        $pendingLeaves = $allLeaves->where('status', 'pending')->count();
        $rejectedLeaves = $allLeaves->where('status', 'rejected')->count();
        
        // Calculate total leave days using model accessor
        $totalLeaveDays = $allLeaves->sum('duration');
        
        // Current month leave days
        $monthLeaveDays = $allLeaves->filter(function($leave) use ($currentMonthStart, $currentMonthEnd) {
            return $leave->start_date >= $currentMonthStart && $leave->start_date <= $currentMonthEnd;
        })->sum('duration');
        
        // Leave by type
        $leaveByType = [
            'sick' => $allLeaves->where('type', 'sick')->count(),
            'vacation' => $allLeaves->where('type', 'vacation')->count(),
            'personal' => $allLeaves->where('type', 'personal')->count(),
        ];
        
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
            'hours_worked' => [
                'total' => round($totalHoursWorked, 1),
                'month' => round($monthHoursWorked, 1),
                'week' => round($weekHoursWorked, 1),
            ],
            'leave_analytics' => [
                'total_leaves' => $totalLeaves,
                'approved' => $approvedLeaves,
                'pending' => $pendingLeaves,
                'rejected' => $rejectedLeaves,
                'total_days' => $totalLeaveDays,
                'month_days' => $monthLeaveDays,
                'by_type' => $leaveByType,
            ],
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
        $leaves = \App\Models\Leave::all();

        // Calculate attendance summaries for different periods
        $attendanceSummaries = [];
        
        foreach ($users as $user) {
            $userAttendances = $attendances->where('user_id', $user->id);
            
            // Current week stats
            $currentWeekStart = now()->startOfWeek();
            $currentWeekEnd = now()->endOfWeek();
            $weekAttendances = $userAttendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
                return $att->attendance_date >= $currentWeekStart && $att->attendance_date <= $currentWeekEnd;
            });
            
            // Current month stats
            $currentMonthStart = now()->startOfMonth();
            $currentMonthEnd = now()->endOfMonth();
            $monthAttendances = $userAttendances->filter(function($att) use ($currentMonthStart, $currentMonthEnd) {
                return $att->attendance_date >= $currentMonthStart && $att->attendance_date <= $currentMonthEnd;
            });
            
            // Count attended (present + late)
            $weekAttended = $weekAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();
            
            $monthAttended = $monthAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();
            
            $totalAttended = $userAttendances->filter(function($att) {
                return in_array($att->status, ['present', 'late']);
            })->count();
            
            // Calculate hours worked
            $weekHoursWorked = $weekAttendances->sum('hours_worked');
            $monthHoursWorked = $monthAttendances->sum('hours_worked');
            $totalHoursWorked = $userAttendances->sum('hours_worked');
            
            $attendanceSummaries[$user->id] = [
                'user' => $user,
                'week' => [
                    'attended' => $weekAttended,
                    'late' => $weekAttendances->where('status', 'late')->count(),
                    'early' => $weekAttendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'not_attended' => 7 - $weekAttendances->count(),
                    'hours_worked' => round($weekHoursWorked, 1),
                ],
                'month' => [
                    'attended' => $monthAttended,
                    'late' => $monthAttendances->where('status', 'late')->count(),
                    'early' => $monthAttendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'not_attended' => now()->daysInMonth - $monthAttendances->count(),
                    'hours_worked' => round($monthHoursWorked, 1),
                ],
                'total' => [
                    'attended' => $totalAttended,
                    'late' => $userAttendances->where('status', 'late')->count(),
                    'early' => $userAttendances->filter(function($att) {
                        return in_array($att->status, ['present', 'late']) && $att->check_in_time && $att->check_in_time <= '09:00:00';
                    })->count(),
                    'hours_worked' => round($totalHoursWorked, 1),
                ]
            ];
        }
        
        // Calculate overall summary
        $summary = [
            'week' => ['attended' => 0, 'late' => 0, 'early' => 0, 'not_attended' => 0, 'hours_worked' => 0],
            'month' => ['attended' => 0, 'late' => 0, 'early' => 0, 'not_attended' => 0, 'hours_worked' => 0],
            'total' => ['attended' => 0, 'late' => 0, 'early' => 0, 'hours_worked' => 0]
        ];
        
        foreach ($attendanceSummaries as $data) {
            $summary['week']['attended'] += $data['week']['attended'];
            $summary['week']['late'] += $data['week']['late'];
            $summary['week']['early'] += $data['week']['early'];
            $summary['week']['not_attended'] += $data['week']['not_attended'];
            $summary['week']['hours_worked'] += $data['week']['hours_worked'];

            $summary['month']['attended'] += $data['month']['attended'];
            $summary['month']['late'] += $data['month']['late'];
            $summary['month']['early'] += $data['month']['early'];
            $summary['month']['not_attended'] += $data['month']['not_attended'];
            $summary['month']['hours_worked'] += $data['month']['hours_worked'];

            $summary['total']['attended'] += $data['total']['attended'];
            $summary['total']['late'] += $data['total']['late'];
            $summary['total']['early'] += $data['total']['early'];
            $summary['total']['hours_worked'] += $data['total']['hours_worked'];
        }

        // Analytics data
        $totalAttendance = $attendances->count();
        $totalPresent = $attendances->where('status', 'present')->count();
        $totalLate = $attendances->where('status', 'late')->count();
        
        // Calculate attendance rate based on present + late vs total
        $attendanceRate = $totalAttendance > 0 ? round((($totalPresent + $totalLate) / $totalAttendance) * 100, 2) : 0;
        
        // Calculate total hours worked using model accessor
        $totalHoursWorked = $attendances->sum('hours_worked');
        
        // Calculate current month hours worked
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $monthHoursWorked = $attendances->filter(function($att) use ($currentMonthStart, $currentMonthEnd) {
            return $att->attendance_date >= $currentMonthStart && $att->attendance_date <= $currentMonthEnd;
        })->sum('hours_worked');
        
        // Calculate current week hours worked
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();
        $weekHoursWorked = $attendances->filter(function($att) use ($currentWeekStart, $currentWeekEnd) {
            return $att->attendance_date >= $currentWeekStart && $att->attendance_date <= $currentWeekEnd;
        })->sum('hours_worked');
        
        // Leave analytics
        $totalLeaves = $leaves->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();
        
        // Calculate total leave days using model accessor
        $totalLeaveDays = $leaves->sum('duration');
        
        // Current month leave days
        $monthLeaveDays = $leaves->filter(function($leave) use ($currentMonthStart, $currentMonthEnd) {
            return $leave->start_date >= $currentMonthStart && $leave->start_date <= $currentMonthEnd;
        })->sum('duration');
        
        // Leave by type
        $leaveByType = [
            'sick' => $leaves->where('type', 'sick')->count(),
            'vacation' => $leaves->where('type', 'vacation')->count(),
            'personal' => $leaves->where('type', 'personal')->count(),
        ];
        
        // Detailed leave data with user information
        $leaveDetails = [];
        foreach ($leaves as $leave) {
            $leaveDetails[] = [
                'user' => $leave->user,
                'type' => $leave->type,
                'start_date' => $leave->start_date->format('M d, Y'),
                'end_date' => $leave->end_date->format('M d, Y'),
                'duration' => $leave->duration,
                'status' => $leave->status,
                'reason' => $leave->reason,
            ];
        }
        
        // Separate users by role with department info
        $staffUsers = $users->where('role', 'staff');
        $attacheeUsers = $users->where('role', 'attachee');
        
        $analytics = [
            'total_users' => $users->count(),
            'total_attendance_records' => $totalAttendance,
            'attendance_rate' => $attendanceRate,
            'late_rate' => $totalAttendance > 0 ? round(($totalLate / $totalAttendance) * 100, 2) : 0,
            'by_role' => [
                'staff' => [
                    'total' => $staffUsers->count(),
                    'attendance' => $attendances->filter(function($att) {
                        return $att->user->role === 'staff';
                    })->count(),
                    'users' => $staffUsers,
                ],
                'attachee' => [
                    'total' => $attacheeUsers->count(),
                    'attendance' => $attendances->filter(function($att) {
                        return $att->user->role === 'attachee';
                    })->count(),
                    'users' => $attacheeUsers,
                ]
            ],
            'monthly_trend' => $this->getMonthlyTrend($attendances),
            'hours_worked' => [
                'total' => round($totalHoursWorked, 1),
                'month' => round($monthHoursWorked, 1),
                'week' => round($weekHoursWorked, 1),
            ],
            'leave_analytics' => [
                'total_leaves' => $totalLeaves,
                'approved' => $approvedLeaves,
                'pending' => $pendingLeaves,
                'rejected' => $rejectedLeaves,
                'total_days' => $totalLeaveDays,
                'month_days' => $monthLeaveDays,
                'by_type' => $leaveByType,
                'details' => $leaveDetails,
            ],
        ];

        $pdf = PDF::loadView('admin.reports-pdf', compact('analytics', 'attendances', 'attendanceSummaries', 'summary'));
        
        return $pdf->download('lish-ai-attendance-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
