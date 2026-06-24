@extends('layouts.admin')

@section('content')
@php $title = 'Leave Request Details'; @endphp
<style>
    .leave-container {
        max-width: 900px;
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
        margin-bottom: 24px;
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

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        font-size: 16px;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
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

    .actions-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .actions-card h2 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 16px 0;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        margin-bottom: 8px;
    }

    .form-group textarea {
        width: 100%;
        padding: 14px 16px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: rgba(124,58,237,.5);
        background: rgba(255,255,255,.08);
    }

    .btn-group {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        flex: 1;
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all .2s;
        border: none;
    }

    .btn-approve {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
    }

    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16,185,129,.3);
    }

    .btn-reject {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239,68,68,.3);
    }
</style>

<div class="leave-container">
    <a href="{{ route('admin.leave.index') }}" class="btn-back">
        <i class="ti ti-arrow-left"></i>
        Back to Leave Management
    </a>

    <div class="leave-card">
        <div class="leave-header">
            <div>
                <div class="user-info">
                    <div class="user-avatar">{{ substr($leave->user->name, 0, 1) }}</div>
                    <div class="user-name">{{ $leave->user->name }}</div>
                </div>
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

    @if($leave->status === 'pending')
        <div class="actions-card">
            <h2>Take Action</h2>
            <form action="{{ route('admin.leave.approve', $leave) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Admin Notes (Optional)</label>
                    <textarea name="admin_notes" placeholder="Add any notes for the user..."></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this leave request?');">
                        <i class="ti ti-check"></i> Approve
                    </button>
                    <button type="button" class="btn-action btn-reject" onclick="showRejectForm()">
                        <i class="ti ti-x"></i> Reject
                    </button>
                </div>
            </form>

            <form id="rejectForm" action="{{ route('admin.leave.reject', $leave) }}" method="POST" style="display: none; margin-top: 16px;">
                @csrf
                <div class="form-group">
                    <label>Rejection Reason (Required)</label>
                    <textarea name="admin_notes" required placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn-action btn-reject">
                        <i class="ti ti-x"></i> Confirm Rejection
                    </button>
                    <button type="button" class="btn-action" style="background: rgba(255,255,255,.1); color: #fff;" onclick="hideRejectForm()">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    function showRejectForm() {
        document.getElementById('rejectForm').style.display = 'block';
    }

    function hideRejectForm() {
        document.getElementById('rejectForm').style.display = 'none';
    }
</script>
@endsection
