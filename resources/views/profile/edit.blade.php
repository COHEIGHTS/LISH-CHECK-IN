@extends('layouts.app-dashboard')

@section('content')
@php $title = 'Profile'; @endphp

<style>
    .profile-container {
        max-width: 7xl;
        margin: 0 auto;
    }

    .profile-section {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,.7);
        margin-bottom: 8px;
    }

    .form-input {
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

    .form-input:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .2s;
    }

    .btn-primary:hover { opacity: .9; }

    .btn-danger {
        background: rgba(248,113,113,.15);
        color: #f87171;
        border: 1px solid rgba(248,113,113,.3);
        border-radius: 8px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-danger:hover {
        background: rgba(248,113,113,.25);
        border-color: rgba(248,113,113,.4);
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
</style>

<div class="profile-container">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="profile-section">
        <div class="section-title">Profile Information</div>
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="profile-section">
        <div class="section-title">Update Password</div>
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="profile-section">
        <div class="section-title">Delete Account</div>
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
