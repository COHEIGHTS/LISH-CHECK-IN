@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Staff Dashboard'; @endphp
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
        transition: transform .2s, border-color .2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(14,165,233,.3);
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

    .quick-actions {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .action-btn {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px;
        padding: 16px;
        color: #fff;
        text-decoration: none;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-btn:hover {
        background: rgba(14,165,233,.1);
        border-color: rgba(14,165,233,.3);
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 24px;
        color: #38bdf8;
    }

    .action-btn-text {
        font-size: 14px;
        font-weight: 600;
    }

    .action-btn-sub {
        font-size: 11px;
        color: rgba(255,255,255,.5);
    }

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

        .quick-actions {
            padding: 16px;
        }

        .section-title {
            font-size: 16px;
        }

        .action-grid {
            grid-template-columns: 1fr;
        }

        .action-btn {
            padding: 12px;
        }

        .action-btn i {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Attendance Days</div>
        <div class="stat-value">{{ $totalAttendanceDays }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Today's Status</div>
        <div class="stat-value">{{ $todayStatus }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Attendance Rate</div>
        <div class="stat-value">{{ $attendanceRate }}%</div>
    </div>
</div>

<div class="quick-actions" style="margin-bottom: 32px;">
    <div class="section-title">Attendance Summary</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
        <div style="background: rgba(255,255,255,.03); border-radius: 12px; padding: 16px; border: 1px solid rgba(255,255,255,.08);">
            <div style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,.5); text-transform: uppercase; margin-bottom: 8px;">This Week</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Present</span>
                <span style="color: #22c55e; font-weight: 700; font-size: 18px;">{{ $summaries['weekly']['present'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Late</span>
                <span style="color: #eab308; font-weight: 700; font-size: 18px;">{{ $summaries['weekly']['late'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Total</span>
                <span style="color: #fff; font-weight: 700; font-size: 18px;">{{ $summaries['weekly']['total'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Hours Worked</span>
                <span style="color: #a78bfa; font-weight: 700; font-size: 18px;">{{ number_format($summaries['weekly']['hours_worked'], 1) }}h</span>
            </div>
        </div>
        <div style="background: rgba(255,255,255,.03); border-radius: 12px; padding: 16px; border: 1px solid rgba(255,255,255,.08);">
            <div style="font-size: 11px; font-weight: 600; color: rgba(255,255,255,.5); text-transform: uppercase; margin-bottom: 8px;">This Month</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Present</span>
                <span style="color: #22c55e; font-weight: 700; font-size: 18px;">{{ $summaries['monthly']['present'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Late</span>
                <span style="color: #eab308; font-weight: 700; font-size: 18px;">{{ $summaries['monthly']['late'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Total</span>
                <span style="color: #fff; font-weight: 700; font-size: 18px;">{{ $summaries['monthly']['total'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: rgba(255,255,255,.7); font-size: 14px;">Hours Worked</span>
                <span style="color: #a78bfa; font-weight: 700; font-size: 18px;">{{ number_format($summaries['monthly']['hours_worked'], 1) }}h</span>
            </div>
        </div>
    </div>
</div>

<div class="quick-actions">
    <div class="section-title">Quick Actions</div>
    <div class="action-grid">
        <a href="{{ route('qr.scan') }}" class="action-btn">
            <i class="ti ti-qr-code"></i>
            <div>
                <div class="action-btn-text">Scan QR Code</div>
                <div class="action-btn-sub">Check in today</div>
            </div>
        </a>
        <a href="{{ route('attendance.history') }}" class="action-btn">
            <i class="ti ti-calendar"></i>
            <div>
                <div class="action-btn-text">Attendance History</div>
                <div class="action-btn-sub">View records</div>
            </div>
        </a>
        <a href="{{ route('profile.edit') }}" class="action-btn">
            <i class="ti ti-user"></i>
            <div>
                <div class="action-btn-text">My Profile</div>
                <div class="action-btn-sub">Update information</div>
            </div>
        </a>
    </div>
</div>
@endsection
