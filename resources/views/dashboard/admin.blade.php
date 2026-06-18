@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Admin Dashboard'; @endphp
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
        border-color: rgba(124,58,237,.3);
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
        background: rgba(124,58,237,.1);
        border-color: rgba(124,58,237,.3);
        transform: translateY(-2px);
    }

    .action-btn i {
        font-size: 24px;
        color: #a78bfa;
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
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ App\Models\User::count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Approvals</div>
        <div class="stat-value">{{ App\Models\User::where('status', 'pending')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Staff Members</div>
        <div class="stat-value">{{ App\Models\User::where('role', 'staff')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Attachees</div>
        <div class="stat-value">{{ App\Models\User::where('role', 'attachee')->count() }}</div>
    </div>
</div>

<div class="quick-actions">
    <div class="section-title">Quick Actions</div>
    <div class="action-grid">
        <a href="{{ route('admin.approval') }}" class="action-btn">
            <i class="ti ti-user-check"></i>
            <div>
                <div class="action-btn-text">Manage Approvals</div>
                <div class="action-btn-sub">Review pending users</div>
            </div>
        </a>
        <a href="#" class="action-btn">
            <i class="ti ti-users"></i>
            <div>
                <div class="action-btn-text">User Management</div>
                <div class="action-btn-sub">View all users</div>
            </div>
        </a>
        <a href="{{ route('qr.generate') }}" class="action-btn">
            <i class="ti ti-qr-code"></i>
            <div>
                <div class="action-btn-text">Generate QR Code</div>
                <div class="action-btn-sub">Daily attendance</div>
            </div>
        </a>
        <a href="#" class="action-btn">
            <i class="ti ti-chart-bar"></i>
            <div>
                <div class="action-btn-text">Attendance Reports</div>
                <div class="action-btn-sub">View statistics</div>
            </div>
        </a>
    </div>
</div>
@endsection
