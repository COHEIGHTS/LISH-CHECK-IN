@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Attendance History'; @endphp
<style>
    .history-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .search-filters {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .search-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 12px 16px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: border-color .2s;
    }

    .search-input:focus {
        border-color: rgba(124,58,237,.5);
    }

    .search-input::placeholder {
        color: rgba(255,255,255,.4);
    }

    .filter-select {
        padding: 12px 16px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        outline: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .filter-select:focus {
        border-color: rgba(124,58,237,.5);
    }

    .filter-select option {
        background: #1a1a2e;
        color: #fff;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -1px;
    }

    .history-table {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 12px;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }

    .table td {
        padding: 16px 12px;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-present {
        background: rgba(34, 197, 94, .2);
        color: #22c55e;
    }

    .status-late {
        background: rgba(234, 179, 8, .2);
        color: #eab308;
    }

    .status-leave {
        background: rgba(139, 92, 246, .2);
        color: #a78bfa;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,.7);
        text-decoration: none;
        margin-bottom: 24px;
        transition: color .2s;
    }

    .back-link:hover {
        color: #fff;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: all .2s;
    }

    .pagination a:hover {
        background: rgba(124,58,237,.2);
        border-color: rgba(124,58,237,.4);
    }

    .pagination .active {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        border-color: transparent;
    }

    .pagination .disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .history-wrapper {
            padding: 0 12px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-value {
            font-size: 24px;
        }

        .history-table {
            padding: 16px;
        }

        .section-title {
            font-size: 16px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
        }

        .status-badge {
            font-size: 10px;
            padding: 3px 8px;
        }

        .search-form {
            flex-direction: column;
        }

        .search-input, .filter-select {
            width: 100%;
        }

        .pagination {
            gap: 4px;
        }

        .pagination a,
        .pagination span {
            padding: 6px 10px;
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .history-table {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }
    }
</style>

<div class="history-wrapper">
    <a href="{{ url()->previous() }}" class="back-link">
        <i class="ti ti-arrow-left"></i>
        Back to Dashboard
    </a>

    <div class="search-filters">
        <form action="{{ route('attendance.history') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search by date or status..." value="{{ request('search') }}">
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
            </select>
            <button type="submit" class="action-btn btn-activate">Search</button>
            <a href="{{ route('attendance.history') }}" class="action-btn btn-delete">Clear</a>
        </form>
    </div>

    <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Attendances</div>
        <div class="stat-value">{{ $totalAttendances }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Present Days</div>
        <div class="stat-value">{{ $presentCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Late Days</div>
        <div class="stat-value">{{ $lateCount }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Leave Days</div>
        <div class="stat-value">{{ $totalLeaveDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Month</div>
        <div class="stat-value">{{ $monthAttendances->count() + $monthLeaveDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">This Month Leave</div>
        <div class="stat-value">{{ $monthLeaveDays }}</div>
    </div>
</div>

<div class="history-table">
    <div class="section-title">Attendance Records</div>
    @if($attendances->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check-in Time</th>
                    <th>Check-out Time</th>
                    <th>Hours Worked</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                        <td>{{ $attendance->check_in_time ? substr($attendance->check_in_time, 0, 5) : '--:--' }}</td>
                        <td>{{ $attendance->check_out_time ? substr($attendance->check_out_time, 0, 5) : '--:--' }}</td>
                        <td>{{ $attendance->hours_worked > 0 ? $attendance->hours_worked . 'h' : '--' }}</td>
                        <td>
                            <span class="status-badge status-{{ $attendance->status }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: rgba(255,255,255,.5);">
            <i class="ti ti-calendar-off" style="font-size: 48px; margin-bottom: 16px;"></i>
            <p>No attendance records found</p>
        </div>
    @endif

    @if($attendances->hasPages())
        <div class="pagination">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@if($leaves->count() > 0)
<div class="history-table" style="margin-top: 32px;">
    <div class="section-title">Leave Records</div>
    <table class="table">
        <thead>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                    <td>{{ $leave->duration }} day(s)</td>
                    <td>{{ ucfirst($leave->type) }}</td>
                    <td>
                        <span class="status-badge status-{{ $leave->status }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
