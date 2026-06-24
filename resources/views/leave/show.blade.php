@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Leave Request Details'; @endphp
<style>
    .leave-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 24px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,.7);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 24px;
        transition: color .2s;
    }

    .btn-back:hover {
        color: #fff;
    }

    .leave-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 32px;
    }

    .leave-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }

    .leave-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 8px 0;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: rgba(251,191,36,.15);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,.3);
    }

    .status-approved {
        background: rgba(52,211,153,.15);
        color: #34d399;
        border: 1px solid rgba(52,211,153,.3);
    }

    .status-rejected {
        background: rgba(239,68,68,.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,.3);
    }

    .detail-row {
        display: flex;
        margin-bottom: 16px;
    }

    .detail-label {
        width: 150px;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
    }

    .detail-value {
        flex: 1;
        font-size: 14px;
        color: #fff;
    }

    .type-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.7);
    }

    .admin-notes {
        background: rgba(255,255,255,.05);
        border-radius: 12px;
        padding: 16px;
        margin-top: 24px;
    }

    .admin-notes h3 {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        margin: 0 0 8px 0;
    }

    .admin-notes p {
        color: #fff;
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
    }
</style>

<div class="leave-container">
    <a href="{{ route('leave.index') }}" class="btn-back">
        <i class="ti ti-arrow-left"></i>
        Back to My Leave Requests
    </a>

    <div class="leave-card">
        <div class="leave-header">
            <div>
                <h1>Leave Request #{{ $leave->id }}</h1>
                <span class="type-badge">{{ ucfirst($leave->type) }} Leave</span>
            </div>
            <span class="status-badge status-{{ $leave->status }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>

        <div class="detail-row">
            <div class="detail-label">Start Date:</div>
            <div class="detail-value">{{ $leave->start_date->format('F d, Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">End Date:</div>
            <div class="detail-value">{{ $leave->end_date->format('F d, Y') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Duration:</div>
            <div class="detail-value">{{ $leave->duration }} day(s)</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">Requested On:</div>
            <div class="detail-value">{{ $leave->created_at->format('F d, Y \a\t g:i A') }}</div>
        </div>

        @if($leave->reason)
            <div class="detail-row">
                <div class="detail-label">Reason:</div>
                <div class="detail-value">{{ $leave->reason }}</div>
            </div>
        @endif

        @if($leave->admin_notes)
            <div class="admin-notes">
                <h3>Admin Notes</h3>
                <p>{{ $leave->admin_notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
