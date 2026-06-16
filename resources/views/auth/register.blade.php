<x-guest-layout>

<style>
    /* ── Reset & base ── */
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

    /* ── Background ── */
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

    /* ── Card ── */
    .register-wrapper {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 460px;
        padding: 24px 16px;
    }

    .register-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 24px;
        padding: 40px 36px;
    }

    /* ── Header ── */
    .card-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .logo-wrap {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        text-decoration: none;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #7c3aed, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-text {
        font-size: 18px;
        font-weight: 700;
        background: linear-gradient(90deg, #a78bfa, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-title {
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .card-sub {
        font-size: 13px;
        color: rgba(255,255,255,.4);
    }

    /* ── Form fields ── */
    .field { margin-bottom: 18px; }

    .field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.6);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }

    .input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

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

    .field select {
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
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='rgba(255,255,255,0.5)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
    }

    .field select:focus {
        border-color: rgba(124,58,237,.6);
        background: rgba(124,58,237,.07);
        box-shadow: 0 0 0 3px rgba(124,58,237,.15);
    }

    .field select option {
        background: #1a1a2e;
        color: #fff;
        padding: 10px;
    }

    .field input.has-toggle { padding-right: 44px; }

    /* valid / invalid states */
    .field input.is-valid   { border-color: rgba(52,211,153,.5); }
    .field input.is-invalid { border-color: rgba(248,113,113,.5); }

    /* toggle visibility button */
    .toggle-vis {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(255,255,255,.35);
        font-size: 16px;
        line-height: 1;
        padding: 4px;
        transition: color .2s;
        display: flex;
        align-items: center;
    }

    .toggle-vis:hover { color: rgba(255,255,255,.7); }

    /* ── Error messages ── */
    .field-error {
        font-size: 11px;
        color: #f87171;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ── Password strength ── */
    .strength-wrap { margin-top: 10px; }

    .strength-bars {
        display: flex;
        gap: 4px;
        margin-bottom: 5px;
    }

    .strength-bar {
        flex: 1;
        height: 3px;
        border-radius: 10px;
        background: rgba(255,255,255,.1);
        transition: background .3s;
    }

    .strength-bar.active-weak   { background: #f87171; }
    .strength-bar.active-fair   { background: #fbbf24; }
    .strength-bar.active-good   { background: #34d399; }
    .strength-bar.active-strong { background: #38bdf8; }

    .strength-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .strength-label {
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,.4);
        transition: color .3s;
    }

    .strength-hints {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .hint {
        font-size: 10px;
        display: flex;
        align-items: center;
        gap: 3px;
        color: rgba(255,255,255,.35);
        transition: color .2s;
    }

    .hint.met { color: #34d399; }
    .hint i   { font-size: 11px; }

    /* match indicator */
    .match-msg {
        font-size: 11px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .match-msg.ok  { color: #34d399; }
    .match-msg.bad { color: #f87171; }

    /* ── Divider ── */
    .divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,.07);
        margin: 24px 0;
    }

    /* ── Submit button ── */
    .btn-register {
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

    .btn-register:hover { opacity: .9; transform: translateY(-1px); }
    .btn-register:active { transform: translateY(0); }

    /* ── Footer link ── */
    .login-link {
        text-align: center;
        margin-top: 20px;
        font-size: 13px;
        color: rgba(255,255,255,.4);
    }

    .login-link a {
        color: #a78bfa;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
    }

    .login-link a:hover { color: #c4b5fd; }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

<div class="mesh-bg"></div>
<div class="grid-overlay"></div>

<div class="register-wrapper">
    <div class="register-card">

        {{-- Header --}}
        <div class="card-header">
            <a href="{{ url('/') }}" class="logo-wrap">
                <div class="logo-icon">
                    <i class="ti ti-scan" style="color:#fff; font-size:20px;"></i>
                </div>
                <span class="logo-text">Lish AI Labs</span>
            </a>
            <div class="card-title">Create your account</div>
            <div class="card-sub">Set up your profile to get started</div>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            {{-- Name --}}
            <div class="field">
                <label for="name">Full Name</label>
                <div class="input-wrap">
                    <i class="ti ti-user input-icon"></i>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="Jane Doe"
                           required autofocus autocomplete="name" />
                </div>
                @error('name')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="ti ti-mail input-icon"></i>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="jane@company.com"
                           required autocomplete="username" />
                </div>
                @error('email')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Role --}}
            <div class="field">
                <label for="role">Role</label>
                <div class="input-wrap">
                    <i class="ti ti-users input-icon"></i>
                    <select id="role" name="role" required>
                        <option value="">Select your role</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="attachee" {{ old('role') === 'attachee' ? 'selected' : '' }}>Attachee</option>
                    </select>
                </div>
                @error('role')
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
                           placeholder="Create a strong password"
                           required autocomplete="new-password" />
                    <button type="button" class="toggle-vis" onclick="toggleVis('password','eyeIcon1')" tabindex="-1" aria-label="Toggle password visibility">
                        <i class="ti ti-eye" id="eyeIcon1"></i>
                    </button>
                </div>

                {{-- Strength meter --}}
                <div class="strength-wrap" id="strengthWrap" style="display:none;">
                    <div class="strength-bars">
                        <div class="strength-bar" id="bar1"></div>
                        <div class="strength-bar" id="bar2"></div>
                        <div class="strength-bar" id="bar3"></div>
                        <div class="strength-bar" id="bar4"></div>
                    </div>
                    <div class="strength-row">
                        <span class="strength-label" id="strengthLabel">Weak</span>
                    </div>
                    <div class="strength-hints">
                        <span class="hint" id="hintLen"><i class="ti ti-circle-check"></i> 8+ chars</span>
                        <span class="hint" id="hintUpper"><i class="ti ti-circle-check"></i> Uppercase</span>
                        <span class="hint" id="hintNum"><i class="ti ti-circle-check"></i> Number</span>
                        <span class="hint" id="hintSpecial"><i class="ti ti-circle-check"></i> Symbol</span>
                    </div>
                </div>

                @error('password')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrap">
                    <i class="ti ti-lock-check input-icon"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="has-toggle"
                           placeholder="Re-enter your password"
                           required autocomplete="new-password" />
                    <button type="button" class="toggle-vis" onclick="toggleVis('password_confirmation','eyeIcon2')" tabindex="-1" aria-label="Toggle confirm password visibility">
                        <i class="ti ti-eye" id="eyeIcon2"></i>
                    </button>
                </div>
                <div class="match-msg" id="matchMsg" style="display:none;"></div>
                @error('password_confirmation')
                    <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <hr class="divider">

            <button type="submit" class="btn-register">
                <i class="ti ti-rocket"></i>
                Create Account
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>

    </div>
</div>

<script>
    /* ── Show / hide password ── */
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

    /* ── Password strength ── */
    const pwdInput   = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthWrap = document.getElementById('strengthWrap');
    const strengthLabel = document.getElementById('strengthLabel');
    const bars = [
        document.getElementById('bar1'),
        document.getElementById('bar2'),
        document.getElementById('bar3'),
        document.getElementById('bar4'),
    ];
    const hintLen     = document.getElementById('hintLen');
    const hintUpper   = document.getElementById('hintUpper');
    const hintNum     = document.getElementById('hintNum');
    const hintSpecial = document.getElementById('hintSpecial');
    const matchMsg    = document.getElementById('matchMsg');

    const levels = [
        { label: 'Weak',   cls: 'active-weak',   active: 1, color: '#f87171' },
        { label: 'Fair',   cls: 'active-fair',   active: 2, color: '#fbbf24' },
        { label: 'Good',   cls: 'active-good',   active: 3, color: '#34d399' },
        { label: 'Strong', cls: 'active-strong', active: 4, color: '#38bdf8' },
    ];

    function scorePassword(val) {
        let score = 0;
        const checks = {
            len:     val.length >= 8,
            upper:   /[A-Z]/.test(val),
            num:     /[0-9]/.test(val),
            special: /[^A-Za-z0-9]/.test(val),
        };
        if (checks.len)     score++;
        if (checks.upper)   score++;
        if (checks.num)     score++;
        if (checks.special) score++;
        return { score, checks };
    }

    function updateHint(el, met) {
        el.classList.toggle('met', met);
    }

    pwdInput.addEventListener('input', function () {
        const val = this.value;

        if (!val) {
            strengthWrap.style.display = 'none';
            bars.forEach(b => { b.className = 'strength-bar'; });
            return;
        }

        strengthWrap.style.display = 'block';
        const { score, checks } = scorePassword(val);
        const level = levels[score - 1] || levels[0];

        // Update bars
        bars.forEach((b, i) => {
            b.className = 'strength-bar';
            if (i < level.active) b.classList.add(level.cls);
        });

        strengthLabel.textContent = level.label;
        strengthLabel.style.color = level.color;

        // Hints
        updateHint(hintLen,     checks.len);
        updateHint(hintUpper,   checks.upper);
        updateHint(hintNum,     checks.num);
        updateHint(hintSpecial, checks.special);

        // Re-check match if confirm has a value
        if (confirmInput.value) checkMatch();
    });

    /* ── Confirm password match ── */
    function checkMatch() {
        const pwd  = pwdInput.value;
        const conf = confirmInput.value;
        if (!conf) { matchMsg.style.display = 'none'; return; }

        matchMsg.style.display = 'flex';
        if (pwd === conf) {
            matchMsg.className = 'match-msg ok';
            matchMsg.innerHTML = '<i class="ti ti-circle-check"></i> Passwords match';
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        } else {
            matchMsg.className = 'match-msg bad';
            matchMsg.innerHTML = '<i class="ti ti-circle-x"></i> Passwords do not match';
            confirmInput.classList.remove('is-valid');
            confirmInput.classList.add('is-invalid');
        }
    }

    confirmInput.addEventListener('input', checkMatch);
</script>

</x-guest-layout>