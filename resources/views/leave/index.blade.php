@extends('layouts.app-dashboard')

@section('content')
@php $title = 'My Leave Requests'; @endphp
<style>
    .leave-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        border: none;
        color: #fff;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(124,58,237,.3);
    }

    .leave-table {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        overflow: hidden;
    }

    .leave-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .leave-table th {
        background: rgba(255,255,255,.08);
        padding: 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .leave-table td {
        padding: 16px;
        border-bottom: 1px solid rgba(255,255,255,.05);
        color: #fff;
    }

    .leave-table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
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

    .type-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        background: rgba(255,255,255,.08);
        color: rgba(255,255,255,.7);
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        border: none;
        text-decoration: none;
    }

    .btn-view {
        background: rgba(14,165,233,.15);
        color: #0ea5e9;
        border: 1px solid rgba(14,165,233,.3);
    }

    .btn-view:hover {
        background: rgba(14,165,233,.25);
    }

    .btn-cancel {
        background: rgba(239,68,68,.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,.3);
    }

    .btn-cancel:hover {
        background: rgba(239,68,68,.25);
    }

    .empty-state {
        text-align: center;
        padding: 64px 24px;
        color: rgba(255,255,255,.5);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
</style>

<div class="leave-container">
    <div class="header">
        <h1>My Leave Requests</h1>
        <a href="{{ route('leave.create') }}" class="btn-primary">
            <i class="ti ti-plus"></i>
            Request Leave
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(52,211,153,.15); border: 1px solid rgba(52,211,153,.3); color: #34d399; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if($leaves->isEmpty())
        <div class="leave-table">
            <div class="empty-state">
                <i class="ti ti-calendar-off"></i>
                <p>No leave requests yet. Click "Request Leave" to submit your first request.</p>
            </div>
        </div>
    @else
        <div class="leave-table">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                        <tr>
                            <td>
                                <span class="type-badge">{{ ucfirst($leave->type) }}</span>
                            </td>
                            <td>{{ $leave->start_date->format('M d, Y') }}</td>
                            <td>{{ $leave->end_date->format('M d, Y') }}</td>
                            <td>{{ $leave->duration }} day(s)</td>
                            <td>
                                <span class="status-badge status-{{ $leave->status }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('leave.show', $leave) }}" class="btn-action btn-view">View</a>
                                @if($leave->status === 'pending')
                                    <form action="{{ route('leave.destroy', $leave) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Are you sure you want to cancel this leave request?');">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
