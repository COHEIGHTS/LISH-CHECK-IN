@extends('layouts.app-dashboard')

@section('content')
@php
    $title = 'Mark Attendance';
    $user = auth()->user();
    $today = now()->format('Y-m-d');
    $todayAttendance = \App\Models\Attendance::where('user_id', $user->id)
        ->where('attendance_date', $today)
        ->first();
@endphp

<style>
    .scan-wrapper {
        max-width: 500px;
        margin: 0 auto;
    }

    .scan-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 24px;
        padding: 40px;
        text-align: center;
    }

    .header {
        margin-bottom: 32px;
    }

    .header h2 {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .header p {
        font-size: 14px;
        color: rgba(255,255,255,.5);
    }

    .manual-input {
        margin-bottom: 24px;
    }

    .manual-input label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.6);
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .manual-input input {
        width: 100%;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        font-family: inherit;
        padding: 12px 16px;
        outline: none;
        transition: border-color .2s, background .2s;
    }

    .manual-input input:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit:hover { opacity: .9; }

    .btn-checkout {
        width: 100%;
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
    }

    .btn-checkout:hover { opacity: .9; }

    .status-display {
        background: rgba(124,58,237,.1);
        border: 1px solid rgba(124,58,237,.3);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: left;
    }

    .status-display p {
        font-size: 13px;
        color: #a78bfa;
        line-height: 1.6;
        margin: 0;
    }

    .status-display strong {
        color: #c4b5fd;
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

    .info-box {
        background: rgba(52,211,153,.1);
        border: 1px solid rgba(52,211,153,.3);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: left;
    }

    .info-box p {
        font-size: 13px;
        color: #34d399;
        line-height: 1.6;
        margin: 0;
    }
</style>

<div class="scan-wrapper">
    <div class="scan-card">
        <div class="header">
            <h2>Mark Attendance</h2>
            <p>@if($todayAttendance && !$todayAttendance->check_out_time) You're checked in. Time to check out! @else Enter the QR token from your admin to check in @endif</p>
        </div>

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

        @if($todayAttendance)
            <div class="status-display">
                <p>
                    <strong>Today's Status:</strong><br>
                    Check-in: {{ $todayAttendance->check_in_time ? substr($todayAttendance->check_in_time, 0, 5) : '--:--' }}<br>
                    @if($todayAttendance->check_out_time)
                        Check-out: {{ substr($todayAttendance->check_out_time, 0, 5) }}<br>
                        Hours Worked: {{ $todayAttendance->hours_worked }} hours
                    @else
                        Check-out: Not yet<br>
                        <em>Click below to check out</em>
                    @endif
                </p>
            </div>

            @if(!$todayAttendance->check_out_time)
                <form action="{{ route('qr.check-out') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-checkout">
                        <i class="ti ti-logout"></i>
                        Check Out
                    </button>
                </form>
            @endif
        @else
            <div class="info-box">
                <p>
                    <strong>How to mark attendance:</strong><br>
                    1. Ask your admin for today's QR token<br>
                    2. Enter the token below<br>
                    3. Click "Mark Attendance"
                </p>
            </div>

            <form action="{{ route('qr.verify') }}" method="POST">
                @csrf
                <div class="manual-input">
                    <label for="qr_token">QR Token</label>
                    <input
                        type="text"
                        id="qr_token"
                        name="qr_token"
                        placeholder="Enter today's QR token"
                        required
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-check"></i>
                    Mark Attendance
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
