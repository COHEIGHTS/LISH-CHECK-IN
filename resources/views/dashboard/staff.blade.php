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
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Attendance Days</div>
        <div class="stat-value">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Today's Status</div>
        <div class="stat-value">--</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Attendance Rate</div>
        <div class="stat-value">0%</div>
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
        <a href="#" class="action-btn">
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
