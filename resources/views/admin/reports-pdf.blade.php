<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - Lish AI</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #7c3aed;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
        }
        
        .logo svg {
            width: 100%;
            height: 100%;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 800;
            color: #7c3aed;
            letter-spacing: -0.5px;
        }
        
        .report-info {
            text-align: right;
        }
        
        .report-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .report-date {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }
        
        .section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #7c3aed, #0ea5e9);
            border-radius: 2px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 24px;
            border-left: 4px solid #7c3aed;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #7c3aed;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-change {
            font-size: 12px;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        .stat-change.negative {
            color: #ef4444;
        }
        
        .role-breakdown {
            display: flex;
            gap: 20px;
        }
        
        .role-stat {
            flex: 1;
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .role-stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0ea5e9;
            margin-bottom: 6px;
        }
        
        .role-stat-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .chart-container {
            margin-top: 20px;
        }
        
        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            height: 200px;
            padding: 20px 0;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .bar {
            flex: 1;
            background: linear-gradient(135deg, #7c3aed, #0ea5e9);
            border-radius: 6px 6px 0 0;
            position: relative;
            min-height: 20px;
        }
        
        .bar-value {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .bar-label {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: linear-gradient(135deg, #7c3aed, #0ea5e9);
            color: white;
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        th:first-child {
            border-radius: 8px 0 0 0;
        }
        
        th:last-child {
            border-radius: 0 8px 0 0;
        }
        
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #1e293b;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-attended {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-late {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .footer-logo svg {
            width: 30px;
            height: 30px;
        }
        
        .footer-text {
            font-weight: 500;
        }
        
        .page-number {
            text-align: center;
            margin-top: 20px;
            color: #94a3b8;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                    <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
                        <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z" fill="#7c3aed"/>
                    </svg>
                </div>
                <div class="company-name">Lish AI</div>
            </div>
            <div class="report-info">
                <div class="report-title">Attendance Report</div>
                <div class="report-date">Generated on {{ now()->format('F d, Y') }}</div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="section">
            <div class="section-title">Summary Statistics</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $analytics['total_users'] }}</div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-change positive">Active staff & attachees</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $analytics['total_attendance_records'] }}</div>
                    <div class="stat-label">Total Records</div>
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
        </div>

        <!-- Attendance by Role -->
        <div class="section">
            <div class="section-title">Attendance by Role</div>
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

        <!-- Monthly Trend -->
        <div class="section">
            <div class="section-title">Monthly Attendance Trend</div>
            <div class="chart-container">
                @php
                    $maxCount = collect($analytics['monthly_trend'])->max('count');
                    $maxCount = $maxCount > 0 ? $maxCount : 1;
                @endphp
                <div class="bar-chart">
                    @foreach($analytics['monthly_trend'] as $trend)
                        @php
                            $height = ($trend['count'] / $maxCount) * 100;
                        @endphp
                        <div class="bar" style="height: {{ $height }}%;">
                            <div class="bar-value">{{ $trend['count'] }}</div>
                            <div class="bar-label">{{ $trend['month'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Weekly Summary -->
        <div class="section">
            <div class="section-title">This Week's Attendance Summary</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['week']['attended'] }}</div>
                    <div class="stat-label">Total Attended</div>
                    <div class="stat-change positive">Present + Late</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['week']['late'] }}</div>
                    <div class="stat-label">Total Late</div>
                    <div class="stat-change {{ $summary['week']['late'] <= 5 ? 'positive' : 'negative' }}">
                        {{ $summary['week']['late'] <= 5 ? 'Good' : 'High' }}
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['week']['early'] }}</div>
                    <div class="stat-label">Total Early</div>
                    <div class="stat-change positive">Before 8:30 AM</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['week']['not_attended'] }}</div>
                    <div class="stat-label">Not Attended</div>
                    <div class="stat-change negative">This week</div>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="section">
            <div class="section-title">This Month's Attendance Summary</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['month']['attended'] }}</div>
                    <div class="stat-label">Total Attended</div>
                    <div class="stat-change positive">Present + Late</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['month']['late'] }}</div>
                    <div class="stat-label">Total Late</div>
                    <div class="stat-change {{ $summary['month']['late'] <= 20 ? 'positive' : 'negative' }}">
                        {{ $summary['month']['late'] <= 20 ? 'Good' : 'High' }}
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['month']['early'] }}</div>
                    <div class="stat-label">Total Early</div>
                    <div class="stat-change positive">Before 8:30 AM</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['month']['not_attended'] }}</div>
                    <div class="stat-label">Not Attended</div>
                    <div class="stat-change negative">This month</div>
                </div>
            </div>
        </div>

        <!-- Total Summary -->
        <div class="section">
            <div class="section-title">All-Time Attendance Summary</div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['total']['attended'] }}</div>
                    <div class="stat-label">Total Attended</div>
                    <div class="stat-change positive">Present + Late</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['total']['late'] }}</div>
                    <div class="stat-label">Total Late</div>
                    <div class="stat-change {{ $summary['total']['late'] <= 100 ? 'positive' : 'negative' }}">
                        {{ $summary['total']['late'] <= 100 ? 'Good' : 'High' }}
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $summary['total']['early'] }}</div>
                    <div class="stat-label">Total Early</div>
                    <div class="stat-change positive">Before 8:30 AM</div>
                </div>
            </div>
        </div>

        <!-- Detailed User Attendance -->
        <div class="section">
            <div class="section-title">Detailed User Attendance</div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Week Attended</th>
                        <th>Week Late</th>
                        <th>Month Attended</th>
                        <th>Month Late</th>
                        <th>Total Attended</th>
                        <th>Total Late</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceSummaries as $data)
                        <tr>
                            <td>{{ $data['user']->name }}</td>
                            <td>{{ ucfirst($data['user']->role) }}</td>
                            <td>{{ $data['week']['attended'] }}</td>
                            <td>{{ $data['week']['late'] }}</td>
                            <td>{{ $data['month']['attended'] }}</td>
                            <td>{{ $data['month']['late'] }}</td>
                            <td>{{ $data['total']['attended'] }}</td>
                            <td>{{ $data['total']['late'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Records -->
        <div class="section">
            <div class="section-title">Recent Attendance Records</div>
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
                                <span class="status-badge {{ $attendance->status === 'late' ? 'status-late' : 'status-attended' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">
                <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
                    <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z" fill="#7c3aed"/>
                </svg>
                <span class="footer-text">Lish AI - Attendance Management System</span>
            </div>
            <div class="footer-text">This report was automatically generated on {{ now()->format('F d, Y \a\t g:i A') }}</div>
            <div class="page-number">Page 1 of 1</div>
        </div>
    </div>
</body>
</html>
