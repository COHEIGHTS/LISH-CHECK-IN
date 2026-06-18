@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Attendance Management'; @endphp

<style>
    .attendance-wrapper {
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

    .header {
        margin-bottom: 32px;
    }

    .header h2 {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
    }

    .header p {
        font-size: 14px;
        color: rgba(255,255,255,.5);
    }

    .period-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
    }

    .period-tab {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        background: rgba(255,255,255,.06);
        color: rgba(255,255,255,.5);
        border: 1px solid rgba(255,255,255,.09);
    }

    .period-tab.active {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        border-color: transparent;
    }

    .period-tab:hover:not(.active) {
        background: rgba(255,255,255,.1);
    }

    .attendance-table {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        overflow: hidden;
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

    .stat-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 4px;
    }

    .stat-attended {
        background: rgba(52,211,153,.15);
        color: #34d399;
    }

    .stat-late {
        background: rgba(251,191,36,.15);
        color: #fbbf24;
    }

    .stat-early {
        background: rgba(14,165,233,.15);
        color: #38bdf8;
    }

    .stat-not-attended {
        background: rgba(248,113,113,.15);
        color: #f87171;
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

    .role-staff {
        background: rgba(14,165,233,.15);
        color: #38bdf8;
    }

    .role-attachee {
        background: rgba(139,92,246,.15);
        color: #c4b5fd;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .summary-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .summary-value {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
    }

    .summary-label {
        font-size: 14px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
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
        .attendance-wrapper {
            padding: 0 12px;
        }

        .header h2 {
            font-size: 20px;
        }

        .header p {
            font-size: 12px;
        }

        .period-tabs {
            flex-wrap: wrap;
        }

        .period-tab {
            padding: 6px 12px;
            font-size: 12px;
        }

        .summary-cards {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .summary-card {
            padding: 16px;
        }

        .summary-value {
            font-size: 24px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
        }

        .stat-badge, .role-badge {
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
        .summary-cards {
            grid-template-columns: 1fr 1fr;
        }

        .attendance-table {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }
    }
</style>

<div class="attendance-wrapper">
    <div class="search-filters">
        <form action="{{ route('admin.attendance') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search by name or email..." value="{{ request('search') }}">
            <select name="role" class="filter-select">
                <option value="">All Roles</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="attachee" {{ request('role') == 'attachee' ? 'selected' : '' }}>Attachee</option>
            </select>
            <button type="submit" class="action-btn btn-activate">Search</button>
            <a href="{{ route('admin.attendance') }}" class="action-btn btn-delete">Clear</a>
        </form>
    </div>

    <div class="header">
        <h2>Attendance Management</h2>
        <p>Track user attendance, punctuality, and performance metrics</p>
    </div>

    <div class="period-tabs">
        <button class="period-tab active" onclick="showPeriod('week')">This Week</button>
        <button class="period-tab" onclick="showPeriod('month')">This Month</button>
        <button class="period-tab" onclick="showPeriod('total')">Total</button>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-value" id="total-attended">0</div>
            <div class="summary-label">Total Attended</div>
        </div>
        <div class="summary-card">
            <div class="summary-value" id="total-late">0</div>
            <div class="summary-label">Total Late</div>
        </div>
        <div class="summary-card">
            <div class="summary-value" id="total-early">0</div>
            <div class="summary-label">Total Early</div>
        </div>
        <div class="summary-card">
            <div class="summary-value" id="total-not-attended">0</div>
            <div class="summary-label">Total Not Attended</div>
        </div>
    </div>

    <div class="attendance-table">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Attended</th>
                    <th>Late</th>
                    <th>Early</th>
                    <th>Not Attended</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $data)
                    <tr data-period="week">
                        <td>{{ $data['user']->name }}</td>
                        <td>
                            <span class="role-badge role-{{ $data['user']->role }}">
                                {{ ucfirst($data['user']->role) }}
                            </span>
                        </td>
                        <td><span class="stat-badge stat-attended">{{ $data['week']['attended'] }}</span></td>
                        <td><span class="stat-badge stat-late">{{ $data['week']['late'] }}</span></td>
                        <td><span class="stat-badge stat-early">{{ $data['week']['early'] }}</span></td>
                        <td><span class="stat-badge stat-not-attended">{{ $data['week']['not_attended'] }}</span></td>
                    </tr>
                    <tr data-period="month" style="display: none;">
                        <td>{{ $data['user']->name }}</td>
                        <td>
                            <span class="role-badge role-{{ $data['user']->role }}">
                                {{ ucfirst($data['user']->role) }}
                            </span>
                        </td>
                        <td><span class="stat-badge stat-attended">{{ $data['month']['attended'] }}</span></td>
                        <td><span class="stat-badge stat-late">{{ $data['month']['late'] }}</span></td>
                        <td><span class="stat-badge stat-early">{{ $data['month']['early'] }}</span></td>
                        <td><span class="stat-badge stat-not-attended">{{ $data['month']['not_attended'] }}</span></td>
                    </tr>
                    <tr data-period="total" style="display: none;">
                        <td>{{ $data['user']->name }}</td>
                        <td>
                            <span class="role-badge role-{{ $data['user']->role }}">
                                {{ ucfirst($data['user']->role) }}
                            </span>
                        </td>
                        <td><span class="stat-badge stat-attended">{{ $data['total']['attended'] }}</span></td>
                        <td><span class="stat-badge stat-late">{{ $data['total']['late'] }}</span></td>
                        <td><span class="stat-badge stat-early">{{ $data['total']['early'] }}</span></td>
                        <td><span class="stat-badge stat-not-attended">-</span></td>
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

<script>
    function showPeriod(period) {
        // Update tab styles
        document.querySelectorAll('.period-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');

        // Show/hide rows based on period
        document.querySelectorAll('tbody tr[data-period]').forEach(row => {
            if (row.dataset.period === period) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Update summary cards
        updateSummary(period);
    }

    function updateSummary(period) {
        const attendanceData = @json($attendanceData);
        let totalAttended = 0;
        let totalLate = 0;
        let totalEarly = 0;
        let totalNotAttended = 0;

        Object.values(attendanceData).forEach(data => {
            totalAttended += data[period]['attended'];
            totalLate += data[period]['late'];
            totalEarly += data[period]['early'];
            if (data[period]['not_attended'] !== '-') {
                totalNotAttended += data[period]['not_attended'];
            }
        });

        document.getElementById('total-attended').textContent = totalAttended;
        document.getElementById('total-late').textContent = totalLate;
        document.getElementById('total-early').textContent = totalEarly;
        document.getElementById('total-not-attended').textContent = totalNotAttended;
    }

    // Initialize with week data
    updateSummary('week');
</script>
@endsection
