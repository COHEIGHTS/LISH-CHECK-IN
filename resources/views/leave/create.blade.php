@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Request Leave'; @endphp
<style>
    .leave-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 24px;
    }

    .form-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 32px;
    }

    .form-header {
        margin-bottom: 32px;
    }

    .form-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 8px 0;
    }

    .form-header p {
        color: rgba(255,255,255,.5);
        margin: 0;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 16px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        transition: all .2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: rgba(124,58,237,.5);
        background: rgba(255,255,255,.08);
    }

    .form-group select option {
        background: #1a1a2e;
        color: #fff;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .required {
        color: #ef4444;
    }

    .btn-submit {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        border: none;
        color: #fff;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(124,58,237,.3);
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

    .error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>

<div class="leave-container">
    <a href="{{ route('leave.index') }}" class="btn-back">
        <i class="ti ti-arrow-left"></i>
        Back to My Leave Requests
    </a>

    <div class="form-card">
        <div class="form-header">
            <h1>Request Leave</h1>
            <p>Submit a leave request for approval</p>
        </div>

        <form method="POST" action="{{ route('leave.store') }}">
            @csrf

            <div class="form-group">
                <label>Leave Type <span class="required">*</span></label>
                <select name="type" required>
                    <option value="">Select leave type</option>
                    <option value="sick">Sick Leave</option>
                    <option value="vacation">Vacation</option>
                    <option value="personal">Personal Leave</option>
                    <option value="other">Other</option>
                </select>
                @error('type')
                    <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Start Date <span class="required">*</span></label>
                <input type="date" name="start_date" required>
                @error('start_date')
                    <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>End Date <span class="required">*</span></label>
                <input type="date" name="end_date" required>
                @error('end_date')
                    <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Reason (Optional)</label>
                <textarea name="reason" placeholder="Provide additional details about your leave request..."></textarea>
                @error('reason')
                    <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Submit Leave Request</button>
        </form>
    </div>
</div>
@endsection
