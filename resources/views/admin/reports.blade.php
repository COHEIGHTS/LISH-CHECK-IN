@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Reports & Analytics'; @endphp

<style>
    .reports-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .header {
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
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

    .btn-download {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-download:hover { opacity: .9; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        font-size: 36px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
    }

    .stat-change {
        font-size: 12px;
        margin-top: 8px;
    }

    .stat-change.positive {
        color: #34d399;
    }

    .stat-change.negative {
        color: #f87171;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .chart-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 24px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 16px;
    }

    .chart-container {
        height: 350px;
        position: relative;
    }

    .analytics-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .analytics-card {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 12px;
        padding: 16px;
    }

    .analytics-card-label {
        font-size: 12px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .analytics-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .analytics-card-sub {
        font-size: 11px;
        color: rgba(255,255,255,.4);
    }

    .analytics-card-sub.positive {
        color: #34d399;
    }

    .analytics-card-sub.negative {
        color: #f87171;
    }

    .trend-table {
        width: 100%;
        margin-top: 20px;
    }

    .trend-table th {
        background: rgba(255,255,255,.06);
        padding: 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .trend-table td {
        padding: 12px;
        border-bottom: 1px solid rgba(255,255,255,.06);
        color: #fff;
        font-size: 13px;
    }

    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 12px;
    }

    .trend-indicator.up {
        background: rgba(52, 211, 153, .1);
        color: #34d399;
    }

    .trend-indicator.down {
        background: rgba(248, 113, 113, .1);
        color: #f87171;
    }

    .trend-indicator.stable {
        background: rgba(255,255,255,.1);
        color: rgba(255,255,255,.5);
    }

    .role-breakdown {
        display: flex;
        gap: 16px;
        margin-top: 16px;
    }

    .role-stat {
        flex: 1;
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 12px;
        padding: 16px;
    }

    .role-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .role-stat-label {
        font-size: 12px;
        color: rgba(255,255,255,.5);
        font-weight: 600;
    }

    .table-card {
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

    .table-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
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
        .reports-wrapper {
            padding: 0 12px;
        }

        .header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header h2 {
            font-size: 20px;
        }

        .header p {
            font-size: 12px;
        }

        .btn-download {
            width: 100%;
            justify-content: center;
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

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .chart-card {
            padding: 16px;
        }

        .chart-title {
            font-size: 16px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
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

        .table-card {
            overflow-x: auto;
        }

        th, td {
            white-space: nowrap;
        }
    }
</style>

<div class="reports-wrapper">
    <div class="header">
        <div>
            <h2>Reports & Analytics</h2>
            <p>Comprehensive attendance analytics and performance metrics</p>
        </div>
        <button onclick="downloadPDF()" class="btn-download">
            <i class="ti ti-download"></i>
            Download PDF Report
        </button>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-change positive">Active staff & attachees</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['total_attendance_records'] }}</div>
            <div class="stat-label">Total Attendance Records</div>
            <div class="stat-change positive">All time data</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['attendance_rate'] }}%</div>
            <div class="stat-label">Attendance Rate</div>
            <div class="stat-change {{ $analytics['attendance_rate'] >= 80 ? 'positive' : 'negative' }}">
                {{ $analytics['attendance_rate'] >= 80 ? 'Good' : 'Needs Improvement' }}
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $analytics['late_rate'] }}%</div>
            <div class="stat-label">Late Arrival Rate</div>
            <div class="stat-change {{ $analytics['late_rate'] <= 10 ? 'positive' : 'negative' }}">
                {{ $analytics['late_rate'] <= 10 ? 'Excellent' : 'High' }}
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card" style="grid-column: 1 / -1;">
            <div class="chart-title">Monthly Attendance Analytics</div>
            <div class="chart-container">
                <canvas id="attendanceChart"></canvas>
            </div>
            
            <div class="analytics-summary">
                @php
                    $totalAttendance = collect($analytics['monthly_trend'])->sum('count');
                    $avgAttendance = round($totalAttendance / count($analytics['monthly_trend']), 1);
                    $totalLate = collect($analytics['monthly_trend'])->sum('late_count');
                    $avgLateRate = round(collect($analytics['monthly_trend'])->avg('late_rate'), 1);
                    $avgUniqueUsers = round(collect($analytics['monthly_trend'])->avg('unique_users'), 1);
                    $latestTrend = end($analytics['monthly_trend']);
                    $prevTrend = prev($analytics['monthly_trend']);
                @endphp
                
                <div class="analytics-card">
                    <div class="analytics-card-label">Total Attendance (6 Months)</div>
                    <div class="analytics-card-value">{{ $totalAttendance }}</div>
                    <div class="analytics-card-sub">Avg: {{ $avgAttendance }}/month</div>
                </div>
                
                <div class="analytics-card">
                    <div class="analytics-card-label">Avg Unique Users</div>
                    <div class="analytics-card-value">{{ $avgUniqueUsers }}</div>
                    <div class="analytics-card-sub">Monthly average</div>
                </div>
                
                <div class="analytics-card">
                    <div class="analytics-card-label">Late Arrival Rate</div>
                    <div class="analytics-card-value">{{ $avgLateRate }}%</div>
                    <div class="analytics-card-sub {{ $avgLateRate <= 10 ? 'positive' : 'negative' }}">
                        {{ $avgLateRate <= 10 ? 'Excellent' : 'Needs Attention' }}
                    </div>
                </div>
                
                <div class="analytics-card">
                    <div class="analytics-card-label">Latest Trend</div>
                    <div class="analytics-card-value">{{ $latestTrend['count'] }}</div>
                    <div class="analytics-card-sub {{ $latestTrend['change_percent'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $latestTrend['change_percent'] >= 0 ? '+' : '' }}{{ $latestTrend['change_percent'] }}% vs prev
                    </div>
                </div>
            </div>
            
            <table class="trend-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Attendance</th>
                        <th>Unique Users</th>
                        <th>Late Rate</th>
                        <th>Change</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analytics['monthly_trend'] as $trend)
                        @php
                            $trendClass = $trend['change_percent'] > 0 ? 'up' : ($trend['change_percent'] < 0 ? 'down' : 'stable');
                            $trendIcon = $trend['change_percent'] > 0 ? '↑' : ($trend['change_percent'] < 0 ? '↓' : '→');
                        @endphp
                        <tr>
                            <td>{{ $trend['month'] }}</td>
                            <td>{{ $trend['count'] }}</td>
                            <td>{{ $trend['unique_users'] }}</td>
                            <td>{{ $trend['late_rate'] }}%</td>
                            <td>{{ $trend['change'] >= 0 ? '+' : '' }}{{ $trend['change'] }}</td>
                            <td>
                                <span class="trend-indicator {{ $trendClass }}">
                                    {{ $trendIcon }} {{ abs($trend['change_percent']) }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="chart-card">
            <div class="chart-title">Attendance by Role</div>
            <div class="role-breakdown">
                <div class="role-stat">
                    <div class="role-stat-value">{{ $analytics['by_role']['staff']['total'] }}</div>
                    <div class="role-stat-label">Staff Members</div>
                    <div class="stat-change positive">{{ $analytics['by_role']['staff']['attendance'] }} records</div>
                </div>
                <div class="role-stat">
                    <div class="role-stat-value">{{ $analytics['by_role']['attachee']['total'] }}</div>
                    <div class="role-stat-label">Attachees</div>
                    <div class="stat-change positive">{{ $analytics['by_role']['attachee']['attendance'] }} records</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h3>Recent Attendance Records</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>Check-in Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances->take(10) as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->attendance_date->format('M d, Y') }}</td>
                        <td>{{ $attendance->check_in_time }}</td>
                        <td>
                            <span class="stat-badge {{ $attendance->status === 'late' ? 'stat-late' : 'stat-attended' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
        <div class="pagination">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function downloadPDF() {
        // Call the backend endpoint to generate and download the PDF
        window.location.href = '{{ route('admin.reports.pdf') }}';
    }

    // Initialize Chart.js for monthly attendance trend
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyTrend = @json($analytics['monthly_trend']);
        
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        const labels = monthlyTrend.map(t => t.month);
        const attendanceData = monthlyTrend.map(t => t.count);
        const uniqueUsersData = monthlyTrend.map(t => t.unique_users);
        const lateRateData = monthlyTrend.map(t => t.late_rate);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Attendance',
                        data: attendanceData,
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#7c3aed',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Unique Users',
                        data: uniqueUsersData,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Late Rate (%)',
                        data: lateRateData,
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#f87171',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 12,
                                weight: 600
                            },
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                    if (context.dataset.label === 'Late Rate (%)') {
                                        label += '%';
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.6)',
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.6)',
                            font: {
                                size: 11
                            }
                        },
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.6)',
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>
@endsection
