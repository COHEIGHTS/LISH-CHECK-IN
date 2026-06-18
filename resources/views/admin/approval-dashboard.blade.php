@extends('layouts.app-dashboard')

@section('content')
@php $title = 'User Approvals'; @endphp
@php $title = 'User Approvals'; @endphp

<style>
    .approval-wrapper {
        max-width: 1400px;
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

    .stat-card.pending .stat-value { color: #fbbf24; }
    .stat-card.approved .stat-value { color: #34d399; }
    .stat-card.suspended .stat-value { color: #f87171; }

    .section {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    td {
        padding: 16px;
        font-size: 14px;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }

    tr:last-child td { border-bottom: none; }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending { background: rgba(251,191,36,.15); color: #fbbf24; }
    .status-approved { background: rgba(52,211,153,.15); color: #34d399; }
    .status-rejected { background: rgba(248,113,113,.15); color: #f87171; }
    .status-suspended { background: rgba(248,113,113,.15); color: #f87171; }

    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin { background: rgba(124,58,237,.15); color: #a78bfa; }
    .role-staff { background: rgba(14,165,233,.15); color: #38bdf8; }
    .role-attachee { background: rgba(139,92,246,.15); color: #c4b5fd; }

    .action-btn {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        border: none;
        margin-right: 4px;
    }

    .btn-approve {
        background: rgba(52,211,153,.15);
        color: #34d399;
    }
    .btn-approve:hover { background: rgba(52,211,153,.25); }

    .btn-reject {
        background: rgba(248,113,113,.15);
        color: #f87171;
    }
    .btn-reject:hover { background: rgba(248,113,113,.25); }

    .btn-suspend {
        background: rgba(251,191,36,.15);
        color: #fbbf24;
    }
    .btn-suspend:hover { background: rgba(251,191,36,.25); }

    .btn-activate {
        background: rgba(52,211,153,.15);
        color: #34d399;
    }
    .btn-activate:hover { background: rgba(52,211,153,.25); }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: rgba(255,255,255,.4);
        font-size: 14px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: rgba(52,211,153,.15);
        border: 1px solid rgba(52,211,153,.3);
        color: #34d399;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
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
        .approval-wrapper {
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

        .section {
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

        .action-btn {
            padding: 4px 8px;
            font-size: 11px;
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

        .table-wrap {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }
    }
</style>

@if(session('success'))
    <div class="alert alert-success">
        <i class="ti ti-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="approval-wrapper">
    <div class="search-filters">
        <form action="{{ route('admin.approval') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search by name or email..." value="{{ request('search') }}">
            <select name="role" class="filter-select">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="attachee" {{ request('role') == 'attachee' ? 'selected' : '' }}>Attachee</option>
            </select>
            <button type="submit" class="action-btn btn-approve">Search</button>
            <a href="{{ route('admin.approval') }}" class="action-btn btn-reject">Clear</a>
        </form>
    </div>

    <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-label">Pending Approval</div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card approved">
        <div class="stat-label">Approved Users</div>
        <div class="stat-value">{{ $stats['approved'] }}</div>
    </div>
    <div class="stat-card suspended">
        <div class="stat-label">Suspended Users</div>
        <div class="stat-value">{{ $stats['suspended'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Staff</div>
        <div class="stat-value">{{ $stats['staff'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Attachees</div>
        <div class="stat-value">{{ $stats['attachee'] }}</div>
    </div>
</div>

{{-- Pending Users --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Pending Users ({{ $pendingUsers->count() }})</div>
    </div>
    <div class="table-wrap">
        @if($pendingUsers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('admin.approve', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-approve" title="Approve">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-reject" title="Reject">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No pending users</div>
        @endif
        @if($pendingUsers->hasPages())
            <div class="pagination">
                {{ $pendingUsers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Approved Users --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Approved Users ({{ $approvedUsers->count() }})</div>
    </div>
    <div class="table-wrap">
        @if($approvedUsers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td>
                                <form action="{{ route('admin.suspend', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-suspend" title="Suspend">
                                        <i class="ti ti-ban"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No approved users</div>
        @endif
        @if($approvedUsers->hasPages())
            <div class="pagination">
                {{ $approvedUsers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Suspended Users --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Suspended Users ({{ $suspendedUsers->count() }})</div>
    </div>
    <div class="table-wrap">
        @if($suspendedUsers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suspendedUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="status-badge status-suspended">Suspended</span></td>
                            <td>
                                <form action="{{ route('admin.activate', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-activate" title="Activate">
                                        <i class="ti ti-check"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No suspended users</div>
        @endif
        @if($suspendedUsers->hasPages())
            <div class="pagination">
                {{ $suspendedUsers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Rejected Users --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Rejected Users ({{ $rejectedUsers->count() }})</div>
    </div>
    <div class="table-wrap">
        @if($rejectedUsers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejectedUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="status-badge status-rejected">Rejected</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">No rejected users</div>
        @endif
        @if($rejectedUsers->hasPages())
            <div class="pagination">
                {{ $rejectedUsers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
