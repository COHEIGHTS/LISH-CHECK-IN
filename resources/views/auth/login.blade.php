<x-guest-layout>

<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: #06061a;
        color: #ffffff;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mesh-bg {
        position: fixed;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 20% 10%, rgba(88,56,220,.28) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 80%, rgba(14,165,233,.18) 0%, transparent 55%),
            radial-gradient(ellipse 50% 40% at 60% 30%, rgba(139,92,246,.14) 0%, transparent 50%),
            #06061a;
        z-index: 0;
        pointer-events: none;
    }

    .grid-overlay {
        position: fixed;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
        background-size: 48px 48px;
        z-index: 0;
        pointer-events: none;
    }

    .login-wrapper {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 440px;
        padding: 24px 16px;
    }

    .login-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 24px;
        padding: 40px 36px;
    }

    /* ── Header ── */
    .card-header { text-align: center; margin-bottom: 32px; }

    .logo-wrap {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        text-decoration: none;
    }

    .logo-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        display: flex; align-items: center; justify-content: center;
    }

    .logo-text {
        font-size: 18px; font-weight: 700;
        background: linear-gradient(90deg, #a78bfa, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-title { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 6px; }
    .card-sub   { font-size: 13px; color: rgba(255,255,255,.4); }

    /* ── Session status ── */
    .session-status {
        background: rgba(52,211,153,.1);
        border: 1px solid rgba(52,211,153,.25);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #34d399;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Fields ── */
    .field { margin-bottom: 18px; }

    .field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.6);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }

    .input-wrap { position: relative; display: flex; align-items: center; }

    .input-icon {
        position: absolute;
        left: 14px;
        color: rgba(255,255,255,.3);
        font-size: 16px;
        pointer-events: none;
        line-height: 1;
    }

    .field input {
        width: 100%;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        font-family: inherit;
        padding: 11px 14px 11px 40px;
        outline: none;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }

    .field input::placeholder { color: rgba(255,255,255,.25); }

    .field input:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
        box-shadow: 0 0 0 3px rgba(124,58,237,.15);
    }

    .field input.has-toggle { padding-right: 44px; }

    .toggle-vis {
        position: absolute;
        right: 10px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 6px;
        cursor: pointer;
        color: rgba(255,255,255,.75);
        font-size: 15px;
        line-height: 1;
        padding: 5px 6px;
        transition: background .2s, color .2s, border-color .2s;
        display: flex;
        align-items: center;
    }

    .toggle-vis:hover {
        background: rgba(124,58,237,.25);
        border-color: rgba(124,58,237,.4);
        color: #fff;
    }

    .field-error {
        font-size: 11px;
        color: #f87171;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Remember + Forgot row ── */
    .row-between {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 13px;
        color: rgba(255,255,255,.5);
        user-select: none;
    }

    .remember-label input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: #7c3aed;
        cursor: pointer;
        border-radius: 4px;
    }

    .forgot-link {
        font-size: 12px;
        color: #a78bfa;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
    }

    .forgot-link:hover { color: #c4b5fd; }

    /* ── Submit ── */
    .btn-login {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        font-family: inherit;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: opacity .2s, transform .15s;
        letter-spacing: 0.2px;
    }

    .btn-login:hover  { opacity: .9; transform: translateY(-1px); }
    .btn-login:active { transform: translateY(0); }

    .divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 24px 0; }

    .register-link {
        text-align: center;
        font-size: 13px;
        color: rgba(255,255,255,.4);
    }

    .register-link a {
        color: #a78bfa;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
    }

    .register-link a:hover { color: #c4b5fd; }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

<div class="mesh-bg"></div>
<div class="grid-overlay"></div>

<div class="login-wrapper">
    <div class="login-card">

        {{-- Header --}}
        <div class="card-header">
            <a href="{{ url('/') }}" class="logo-wrap">
                <div class="logo-icon">
                    <i class="ti ti-scan" style="color:#fff; font-size:20px;"></i>
                </div>
                <span class="logo-text">Lish AI Labs</span>
            </a>
            <div class="card-title">Welcome back</div>
            <div class="card-sub">Sign in to your account to continue</div>
        </div>

        {{-- Session status --}}
        @if (session('status'))
            <div class="session-status">
                <i class="ti ti-circle-check"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="ti ti-mail input-icon"></i>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="jane@company.com"
                           required autofocus autocomplete="username" />
                </div>
                @error('email')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="ti ti-lock input-icon"></i>
                    <input id="password" type="password" name="password"
                           class="has-toggle"
                           placeholder="Enter your password"
                           required autocomplete="current-password" />
                    <button type="button" class="toggle-vis" onclick="toggleVis('password','eyeIcon')" tabindex="-1" aria-label="Toggle password visibility">
                        <i class="ti ti-eye" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Remember me + Forgot password --}}
            <div class="row-between">
                <label class="remember-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                <i class="ti ti-login"></i>
                Sign In
            </button>
        </form>

        @if (Route::has('register'))
            <hr class="divider">
            <div class="register-link">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        @endif

    </div>
</div>

<script>
    function toggleVis(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            input.type = 'password';
            icon.className = 'ti ti-eye';
        }
    }
</script>

</x-guest-layout>