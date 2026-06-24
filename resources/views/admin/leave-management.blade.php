@extends('layouts.admin')

@section('content')
@php $title = 'Leave Management'; @endphp
<style>
    .leave-container {
        max-width: 1400px;
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

    .filters {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
    }

    .filter-group select option {
        background: #1a1a2e;
        color: #fff;
    }

    .btn-filter {
        background: rgba(124,58,237,.15);
        border: 1px solid rgba(124,58,237,.3);
        color: #a78bfa;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-filter:hover {
        background: rgba(124,58,237,.25);
    }

    .btn-clear {
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        color: rgba(255,255,255,.7);
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-clear:hover {
        background: rgba(255,255,255,.1);
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
        margin-right: 4px;
    }

    .btn-view {
        background: rgba(14,165,233,.15);
        color: #0ea5e9;
        border: 1px solid rgba(14,165,233,.3);
    }

    .btn-view:hover {
        background: rgba(14,165,233,.25);
    }

    .btn-approve {
        background: rgba(52,211,153,.15);
        color: #34d399;
        border: 1px solid rgba(52,211,153,.3);
    }

    .btn-approve:hover {
        background: rgba(52,211,153,.25);
    }

    .btn-reject {
        background: rgba(239,68,68,.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,.3);
    }

    .btn-reject:hover {
        background: rgba(239,68,68,.25);
    }

    .btn-delete {
        background: rgba(255,255,255,.05);
        color: rgba(255,255,255,.7);
        border: 1px solid rgba(255,255,255,.1);
    }

    .btn-delete:hover {
        background: rgba(239,68,68,.15);
        color: #ef4444;
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

    .pagination {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    .pagination a {
        padding: 8px 16px;
        margin: 0 4px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px;
        color: rgba(255,255,255,.7);
        text-decoration: none;
        transition: all .2s;
    }

    .pagination a:hover,
    .pagination .active {
        background: rgba(124,58,237,.15);
        border-color: rgba(124,58,237,.3);
        color: #a78bfa;
    }
</style>

<div class="leave-container">
    <div class="header">
        <h1>Leave Management</h1>
        @if($pendingCount > 0)
            <div style="background: rgba(251,191,36,.15); border: 1px solid rgba(251,191,36,.3); color: #fbbf24; padding: 12px 20px; border-radius: 12px; font-weight: 600;">
                <i class="ti ti-alert-triangle"></i> {{ $pendingCount }} pending request(s)
            </div>
        @endif
    </div>

    @if(session('success'))
        <div style="background: rgba(52,211,153,.15); border: 1px solid rgba(52,211,153,.3); color: #34d399; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.leave.index') }}" method="GET" class="filters">
        <div class="filter-group">
            <input type="text" name="search" placeholder="Search by user name..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <select name="status">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <select name="type">
                <option value="">All Types</option>
                <option value="sick" {{ request('type') === 'sick' ? 'selected' : '' }}>Sick</option>
                <option value="vacation" {{ request('type') === 'vacation' ? 'selected' : '' }}>Vacation</option>
                <option value="personal" {{ request('type') === 'personal' ? 'selected' : '' }}>Personal</option>
                <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">Filter</button>
        <a href="{{ route('admin.leave.index') }}" class="btn-clear">Clear</a>
    </form>

    @if($leaves->isEmpty())
        <div class="leave-table">
            <div class="empty-state">
                <i class="ti ti-calendar-off"></i>
                <p>No leave requests found.</p>
            </div>
        </div>
    @else
        <div class="leave-table">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
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
                            <td>{{ $leave->user->name }}</td>
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
                                <a href="{{ route('admin.leave.show', $leave) }}" class="btn-action btn-view">View</a>
                                @if($leave->status === 'pending')
                                    <form action="{{ route('admin.leave.approve', $leave) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this leave request?');">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.leave.reject', $leave) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-reject" onclick="return confirm('Reject this leave request?');">Reject</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.leave.destroy', $leave) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Delete this leave request?');"><i class="ti ti-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="pagination">
                {{ $leaves->appends(request()->query())->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
