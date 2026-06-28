@extends('layouts.app-dashboard')

@section('content')
@php $title = 'My Attendance'; @endphp
<style>
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

    .today-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
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

    .status-not-checked-in {
        background: rgba(107, 114, 128, .2);
        color: #6b7280;
    }

    .recent-table {
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

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .summary-card {
        background: rgba(255,255,255,.03);
        border-radius: 12px;
        padding: 16px;
        border: 1px solid rgba(255,255,255,.08);
    }

    .summary-label {
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row span:first-child {
        color: rgba(255,255,255,.7);
        font-size: 14px;
    }

    .summary-row span:last-child {
        font-weight: 700;
        font-size: 18px;
    }

    .text-green { color: #22c55e; }
    .text-yellow { color: #eab308; }
    .text-white { color: #fff; }

    /* Animated Progress Bar */
    .progress-container {
        margin-bottom: 12px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
        font-size: 13px;
        color: rgba(255,255,255,.7);
    }

    .progress-bar {
        height: 8px;
        background: rgba(255,255,255,.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease-out;
        animation: progressAnimation 1.5s ease-out;
    }

    .progress-fill.present {
        background: linear-gradient(90deg, #22c55e, #4ade80);
    }

    .progress-fill.late {
        background: linear-gradient(90deg, #eab308, #facc15);
    }

    @keyframes progressAnimation {
        from { width: 0; }
    }

    /* Animated Circular Progress */
    .circular-progress {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }

    .circular-progress svg {
        transform: rotate(-90deg);
    }

    .circular-progress circle {
        fill: none;
        stroke-width: 8;
    }

    .circular-progress .bg {
        stroke: rgba(255,255,255,.1);
    }

    .circular-progress .progress {
        stroke: url(#gradient);
        stroke-linecap: round;
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        animation: circularProgress 1.5s ease-out forwards;
    }

    @keyframes circularProgress {
        to { stroke-dashoffset: var(--offset); }
    }

    .circular-progress .percentage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: 700;
        color: #fff;
    }

    /* Chart Container */
    .chart-container {
        background: rgba(255,255,255,.03);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,.08);
        margin-bottom: 32px;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .analytics-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }

    @media (max-width: 768px) {
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

        .today-card {
            padding: 16px;
        }

        .section-title {
            font-size: 16px;
        }

        .analytics-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .analytics-card {
            padding: 16px;
        }

        .circular-progress {
            width: 100px;
            height: 100px;
        }

        .circular-progress svg {
            width: 100px;
            height: 100px;
        }

        .circular-progress .percentage {
            font-size: 20px;
        }

        .recent-table {
            padding: 16px;
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

        .summary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }

        .recent-table {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }

        .circular-progress {
            width: 80px;
            height: 80px;
        }

        .circular-progress svg {
            width: 80px;
            height: 80px;
        }

        .circular-progress .percentage {
            font-size: 16px;
        }
    }
</style>

<div class="today-card fade-in">
    <div class="section-title">Today's Status</div>
    @if($todayAttendance)
        <div style="display: flex; align-items: center; gap: 16px;">
            <span class="status-badge status-{{ $todayAttendance->status }}">
                {{ ucfirst($todayAttendance->status) }}
            </span>
            <div style="color: rgba(255,255,255,.7);">
                Check-in: {{ $todayAttendance->check_in_time ? substr($todayAttendance->check_in_time, 0, 5) : '--:--' }}
                @if($todayAttendance->check_out_time)
                    | Check-out: {{ substr($todayAttendance->check_out_time, 0, 5) }}
                @endif
            </div>
        </div>
    @else
        <span class="status-badge status-not-checked-in">Not Checked In</span>
    @endif
</div>

<div class="analytics-grid">
    <div class="analytics-card fade-in">
        <div class="section-title">Weekly Analytics</div>
        
        <div class="circular-progress">
            <svg width="120" height="120">
                <defs>
                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#22c55e;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#4ade80;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <circle class="bg" cx="60" cy="60" r="45"></circle>
                @php
                    $weekPercentage = $weekStats['total'] > 0 ? round(($weekStats['present'] / $weekStats['total']) * 100) : 0;
                    $weekOffset = 283 - (283 * $weekPercentage / 100);
                @endphp
                <circle class="progress" cx="60" cy="60" r="45" style="--offset: {{ $weekOffset }};"></circle>
            </svg>
            <div class="percentage">{{ $weekPercentage }}%</div>
        </div>
        
        <div style="margin-top: 20px;">
            <div class="progress-container">
                <div class="progress-label">
                    <span>Present</span>
                    <span>{{ $weekStats['present'] }} days</span>
                </div>
                <div class="progress-bar">
                    @php $presentWidth = $weekStats['total'] > 0 ? ($weekStats['present'] / $weekStats['total']) * 100 : 0; @endphp
                    <div class="progress-fill present" style="width: {{ $presentWidth }}%;"></div>
                </div>
            </div>
            
            <div class="progress-container">
                <div class="progress-label">
                    <span>Late</span>
                    <span>{{ $weekStats['late'] }} days</span>
                </div>
                <div class="progress-bar">
                    @php $lateWidth = $weekStats['total'] > 0 ? ($weekStats['late'] / $weekStats['total']) * 100 : 0; @endphp
                    <div class="progress-fill late" style="width: {{ $lateWidth }}%;"></div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-label">
                    <span>Hours Worked</span>
                    <span>{{ number_format($weekStats['hours_worked'], 1) }}h</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: linear-gradient(90deg, #a78bfa, #c4b5fd);"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="analytics-card fade-in">
        <div class="section-title">Monthly Analytics</div>
        
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="bg" cx="60" cy="60" r="45"></circle>
                @php
                    $monthPercentage = $monthStats['total'] > 0 ? round(($monthStats['present'] / $monthStats['total']) * 100) : 0;
                    $monthOffset = 283 - (283 * $monthPercentage / 100);
                @endphp
                <circle class="progress" cx="60" cy="60" r="45" style="--offset: {{ $monthOffset }};"></circle>
            </svg>
            <div class="percentage">{{ $monthPercentage }}%</div>
        </div>
        
        <div style="margin-top: 20px;">
            <div class="progress-container">
                <div class="progress-label">
                    <span>Present</span>
                    <span>{{ $monthStats['present'] }} days</span>
                </div>
                <div class="progress-bar">
                    @php $monthPresentWidth = $monthStats['total'] > 0 ? ($monthStats['present'] / $monthStats['total']) * 100 : 0; @endphp
                    <div class="progress-fill present" style="width: {{ $monthPresentWidth }}%;"></div>
                </div>
            </div>
            
            <div class="progress-container">
                <div class="progress-label">
                    <span>Late</span>
                    <span>{{ $monthStats['late'] }} days</span>
                </div>
                <div class="progress-bar">
                    @php $monthLateWidth = $monthStats['total'] > 0 ? ($monthStats['late'] / $monthStats['total']) * 100 : 0; @endphp
                    <div class="progress-fill late" style="width: {{ $monthLateWidth }}%;"></div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-label">
                    <span>Hours Worked</span>
                    <span>{{ number_format($monthStats['hours_worked'], 1) }}h</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%; background: linear-gradient(90deg, #a78bfa, #c4b5fd);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="recent-table">
    <div class="section-title">Recent Activity (Last 7 Days)</div>
    @if($recentAttendances->count() > 0)
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
                @foreach($recentAttendances as $attendance)
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
            <p>No recent activity found</p>
        </div>
    @endif
</div>
@endsection
