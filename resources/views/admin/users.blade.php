@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Users Management'; @endphp

<style>
    .users-wrapper {
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

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
    }

    .users-table {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-header {
        padding: 24px;
        border-bottom: 1px solid rgba(255,255,255,.09);
    }

    .table-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .table-header p {
        font-size: 14px;
        color: rgba(255,255,255,.5);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: rgba(255,255,255,.06);
        padding: 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        color: #fff;
        font-size: 14px;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background: rgba(255,255,255,.02);
    }

    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: rgba(124,58,237,.15);
        color: #a78bfa;
    }

    .role-staff {
        background: rgba(14,165,233,.15);
        color: #38bdf8;
    }

    .role-attachee {
        background: rgba(139,92,246,.15);
        color: #c4b5fd;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(52,211,153,.15);
        color: #34d399;
    }

    .status-pending {
        background: rgba(251,191,36,.15);
        color: #fbbf24;
    }

    .status-suspended {
        background: rgba(248,113,113,.15);
        color: #f87171;
    }

    .status-rejected {
        background: rgba(107,114,128,.15);
        color: #9ca3af;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        border: none;
        margin-right: 4px;
    }

    .btn-suspend {
        background: rgba(248,113,113,.15);
        color: #f87171;
        border: 1px solid rgba(248,113,113,.3);
    }

    .btn-suspend:hover {
        background: rgba(248,113,113,.25);
    }

    .btn-delete {
        background: rgba(239,68,68,.15);
        color: #ef4444;
        border: 1px solid rgba(239,68,68,.3);
    }

    .btn-delete:hover {
        background: rgba(239,68,68,.25);
    }

    .btn-activate {
        background: rgba(52,211,153,.15);
        color: #34d399;
        border: 1px solid rgba(52,211,153,.3);
    }

    .btn-activate:hover {
        background: rgba(52,211,153,.25);
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

    .alert-error {
        background: rgba(248,113,113,.15);
        border: 1px solid rgba(248,113,113,.3);
        color: #f87171;
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
        .users-wrapper {
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

        .table-header {
            padding: 16px;
        }

        .table-header h2 {
            font-size: 16px;
        }

        .table-header p {
            font-size: 12px;
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
            margin-right: 2px;
        }

        .role-badge, .status-badge {
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

        .users-table {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }
    }
</style>

<div class="users-wrapper">
    @if(session('success'))
        <div class="alert alert-success">
            <i class="ti ti-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="ti ti-alert-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="search-filters">
        <form action="{{ route('admin.users') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search by name or email..." value="{{ request('search') }}">
            <select name="role" class="filter-select">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="attachee" {{ request('role') == 'attachee' ? 'selected' : '' }}>Attachee</option>
            </select>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="action-btn btn-activate">Search</button>
            <a href="{{ route('admin.users') }}" class="action-btn btn-delete">Clear</a>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['active'] }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['suspended'] }}</div>
            <div class="stat-label">Suspended</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['staff'] }}</div>
            <div class="stat-label">Staff</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['attachee'] }}</div>
            <div class="stat-label">Attachee</div>
        </div>
    </div>

    <div class="users-table">
        <div class="table-header">
            <h2>All Users</h2>
            <p>Manage user accounts and permissions</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $user->status }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                                @if($user->status === 'active' || $user->status === 'approved')
                                    <form action="{{ route('admin.suspend', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-suspend">Suspend</button>
                                    </form>
                                @elseif($user->status === 'suspended')
                                    <form action="{{ route('admin.activate', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-activate">Activate</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.delete', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    <button type="submit" class="action-btn btn-delete">Delete</button>
                                </form>
                            @else
                                <span style="color: rgba(255,255,255,.3);">Cannot perform actions on yourself</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="pagination">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
