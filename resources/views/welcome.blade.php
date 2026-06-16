<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lish AI Labs — Digital Check-In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #06061a;
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
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

        .content { position: relative; z-index: 1; }

        /* ── Nav ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 48px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            background: rgba(6,6,26,.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color .2s;
        }

        .nav-links a:hover { color: #fff; }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-ghost-sm {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.04);
            transition: background .2s, color .2s;
        }

        .btn-ghost-sm:hover {
            background: rgba(255,255,255,.09);
            color: #fff;
        }

        .btn-primary-sm {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            background: linear-gradient(135deg, #7c3aed, #0ea5e9);
            transition: opacity .2s, transform .15s;
            white-space: nowrap;
        }

        .btn-primary-sm:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        /* ── Hero ── */
        .hero {
            text-align: center;
            padding: 80px 48px 56px;
            max-width: 860px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(124,58,237,.15);
            border: 1px solid rgba(124,58,237,.35);
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 12px;
            color: #c4b5fd;
            font-weight: 500;
            margin-bottom: 28px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #34d399;
            animation: pulseAnim 1.8s ease-in-out infinite;
            flex-shrink: 0;
        }

        @keyframes pulseAnim {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .5; transform: scale(.7); }
        }

        h1 {
            font-size: clamp(36px, 6vw, 70px);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -2px;
            margin-bottom: 20px;
            color: #fff;
        }

        .gradient-word {
            background: linear-gradient(135deg, #a78bfa 0%, #38bdf8 50%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: 18px;
            color: rgba(255,255,255,.55);
            line-height: 1.7;
            max-width: 560px;
            margin: 0 auto 36px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 56px;
        }

        .btn-primary-lg {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7c3aed, #0ea5e9);
            color: #fff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: opacity .2s, transform .15s;
        }

        .btn-primary-lg:hover { opacity: .9; transform: translateY(-1px); }

        .btn-outline-lg {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.05);
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            border: 1px solid rgba(255,255,255,.12);
            transition: background .2s;
        }

        .btn-outline-lg:hover { background: rgba(255,255,255,.09); }

        /* ── Stats ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 16px;
            overflow: hidden;
            max-width: 680px;
            margin: 0 auto 80px;
        }

        .stat-cell {
            background: rgba(255,255,255,.03);
            backdrop-filter: blur(12px);
            padding: 20px 16px;
            text-align: center;
        }

        .stat-num {
            display: block;
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #a78bfa, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
        }

        .stat-label { font-size: 12px; color: rgba(255,255,255,.45); font-weight: 500; }

        /* ── QR showcase ── */
        .qr-showcase {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 48px 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: center;
        }

        .glass-card {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 20px;
            padding: 28px;
        }

        .qr-scanner-visual {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 20px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .qr-frame {
            width: 160px;
            height: 160px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-corner { position: absolute; width: 28px; height: 28px; border-color: #7c3aed; border-style: solid; }
        .qr-corner.tl { top:0; left:0;   border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
        .qr-corner.tr { top:0; right:0;  border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
        .qr-corner.bl { bottom:0; left:0;  border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
        .qr-corner.br { bottom:0; right:0; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

        .scan-line {
            position: absolute;
            width: 80%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #7c3aed, #0ea5e9, transparent);
            border-radius: 2px;
            animation: scanMove 2.4s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(124,58,237,.6);
        }

        @keyframes scanMove {
            0%   { top: 20%; }
            50%  { top: 78%; }
            100% { top: 20%; }
        }

        .qr-pattern { display: grid; grid-template-columns: repeat(7, 18px); gap: 3px; }
        .qr-bit { width: 18px; height: 18px; border-radius: 3px; }
        .qr-bit.on  { background: rgba(167,139,250,.75); }
        .qr-bit.off { background: rgba(255,255,255,.04); }

        .scanner-status {
            font-size: 12px;
            color: #34d399;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .last-checkin {
            background: rgba(52,211,153,.1);
            border: 1px solid rgba(52,211,153,.2);
            border-radius: 10px;
            padding: 10px 16px;
            text-align: center;
            width: 100%;
        }

        /* ── Steps ── */
        .flow-steps { display: flex; flex-direction: column; gap: 14px; }

        .flow-step {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .step-icon.purple { background: rgba(124,58,237,.2); }
        .step-icon.blue   { background: rgba(14,165,233,.2); }
        .step-icon.green  { background: rgba(52,211,153,.15); }
        .step-icon.amber  { background: rgba(251,191,36,.15); }

        /* ── Section ── */
        .section {
            max-width: 1060px;
            margin: 0 auto;
            padding: 0 48px 80px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #a78bfa;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: clamp(26px, 4vw, 40px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1px;
            color: #fff;
            margin-bottom: 12px;
        }

        .section-sub {
            font-size: 16px;
            color: rgba(255,255,255,.45);
            margin-bottom: 40px;
        }

        /* ── Dashboard preview ── */
        .dash-metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .metric-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            padding: 16px;
        }

        .metric-label { font-size: 11px; color: rgba(255,255,255,.4); margin-bottom: 6px; font-weight: 500; }
        .metric-val   { font-size: 26px; font-weight: 800; color: #fff; }
        .metric-up    { font-size: 11px; color: #34d399; margin-top: 2px; }
        .metric-down  { font-size: 11px; color: #f87171; margin-top: 2px; }

        .bar-area {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .bar-label { font-size: 11px; color: rgba(255,255,255,.4); margin-bottom: 12px; }

        .bars {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            height: 60px;
        }

        .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
        .bar-fill { width: 100%; border-radius: 4px 4px 0 0; min-height: 4px; }
        .bar-day  { font-size: 10px; color: rgba(255,255,255,.3); }

        /* ── Recent check-ins ── */
        .recent-checkins { display: flex; flex-direction: column; gap: 8px; }

        .checkin-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 10px;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .checkin-name { font-size: 13px; font-weight: 600; }
        .checkin-dept { font-size: 11px; color: rgba(255,255,255,.4); }
        .checkin-time { font-size: 11px; color: rgba(255,255,255,.35); }

        .badge {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .badge-green  { background: rgba(52,211,153,.15);  color: #34d399; border: 1px solid rgba(52,211,153,.25); }
        .badge-amber  { background: rgba(251,191,36,.12);  color: #fbbf24; border: 1px solid rgba(251,191,36,.2); }
        .badge-purple { background: rgba(167,139,250,.12); color: #c4b5fd; border: 1px solid rgba(167,139,250,.2); }

        /* ── Features grid ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .feature-card {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 16px;
            padding: 24px;
            transition: border-color .2s, background .2s;
        }

        .feature-card:hover {
            border-color: rgba(124,58,237,.3);
            background: rgba(124,58,237,.05);
        }

        .feat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .feat-title { font-size: 15px; font-weight: 600; color: rgba(255,255,255,.9); margin-bottom: 6px; }
        .feat-desc  { font-size: 13px; color: rgba(255,255,255,.45); line-height: 1.6; }

        /* ── Footer ── */
        footer {
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 28px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-links { display: flex; gap: 24px; }

        .footer-links a {
            font-size: 13px;
            color: rgba(255,255,255,.35);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-links a:hover { color: rgba(255,255,255,.7); }

        .footer-copy { font-size: 12px; color: rgba(255,255,255,.25); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            nav { padding: 16px 20px; }
            .nav-links { display: none; }
            .hero { padding: 60px 20px 40px; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .qr-showcase { grid-template-columns: 1fr; padding: 0 20px 60px; }
            .section { padding: 0 20px 60px; }
            .dash-metrics { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: 1fr; }
            footer { padding: 24px 20px; flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="grid-overlay"></div>

    <div class="content">

        {{-- ── Navigation ── --}}
        <nav>
            <a href="{{ url('/') }}" class="nav-logo">
                <div class="logo-icon">
                    <i class="ti ti-scan" style="color:#fff; font-size:18px;" aria-hidden="true"></i>
                </div>
                <span class="logo-text">Lish AI Labs</span>
            </a>

            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">How it works</a></li>
                <li><a href="#dashboard">Dashboard</a></li>
            </ul>

            {{-- Auth navigation --}}
            @if (Route::has('login'))
                <div class="nav-auth">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary-sm">
                            <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost-sm">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary-sm">
                                Get started
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        {{-- ── Hero ── --}}
        <section class="hero">
            <div class="hero-badge">
                <i class="ti ti-sparkles" style="font-size:13px; color:#c4b5fd;" aria-hidden="true"></i>
                AI-Powered Attendance Management
            </div>

            <h1>Check-In &amp; Out for<br><span class="gradient-word">Staff &amp; Attachees</span></h1>

            <p class="hero-sub">
                Staff and attachees scan their unique QR code at the door — arrival and departure are logged instantly.
                No queues, no clipboards, no friction.
            </p>

            <div class="hero-actions">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-primary-lg">
                        <i class="ti ti-login" aria-hidden="true"></i>
                        Sign in to dashboard
                    </a>
                @endif
            </div>

            <div class="stats-row">
                <div class="stat-cell">
                    <span class="stat-num">2</span>
                    <span class="stat-label">User types tracked</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-num">0.4s</span>
                    <span class="stat-label">Scan to confirm</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-num">99.9%</span>
                    <span class="stat-label">Uptime</span>
                </div>
                <div class="stat-cell">
                    <span class="stat-num">24/7</span>
                    <span class="stat-label">Real-time sync</span>
                </div>
            </div>
        </section>

        {{-- ── QR scanner showcase + How it works ── --}}
        <div id="how-it-works" class="qr-showcase">

            {{-- QR scanner visual --}}
            <div class="qr-scanner-visual">
                <p style="font-size:12px; color:rgba(255,255,255,.4); font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:4px;">QR check-in / check-out</p>

                <div class="qr-frame">
                    <div class="qr-corner tl"></div>
                    <div class="qr-corner tr"></div>
                    <div class="qr-corner bl"></div>
                    <div class="qr-corner br"></div>
                    <div class="scan-line"></div>
                    <div class="qr-pattern" id="qrpat"></div>
                </div>

                <div class="scanner-status">
                    <div class="pulse-dot"></div>
                    Scanner active — awaiting scan
                </div>

                <div class="last-checkin">
                    <div style="font-size:11px; color:#34d399; font-weight:600; margin-bottom:3px;">✓ Last check-in</div>
                    <div style="font-size:14px; font-weight:700; color:#fff;">Wanjiku N. · 08:47 AM</div>
                    <div style="font-size:11px; color:rgba(255,255,255,.4);">Engineering · Staff · On time</div>
                </div>
            </div>

            {{-- Steps --}}
            <div>
                <p class="section-label" style="margin-bottom:8px;">How it works</p>
                <h2 style="font-size:26px; font-weight:800; letter-spacing:-0.5px; color:#fff; margin-bottom:8px; line-height:1.2;">
                    Four steps to<br>frictionless attendance
                </h2>
                <p style="font-size:14px; color:rgba(255,255,255,.45); margin-bottom:20px; line-height:1.65;">
                    Every staff member and attachee gets a unique QR code. They scan it at the entrance on arrival and again on departure — the system handles the rest.
                </p>

                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="step-icon purple">
                            <i class="ti ti-id-badge" style="color:#a78bfa;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <strong style="display:block; font-size:14px; font-weight:600; color:rgba(255,255,255,.9); margin-bottom:2px;">
                                Receive a unique QR code
                            </strong>
                            <span style="font-size:12px; color:rgba(255,255,255,.45);">
                                Auto-generated on registration for both staff and attachees — available in the app or as a printed badge
                            </span>
                        </div>
                    </div>

                    <div class="flow-step">
                        <div class="step-icon blue">
                            <i class="ti ti-scan" style="color:#38bdf8;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <strong style="display:block; font-size:14px; font-weight:600; color:rgba(255,255,255,.9); margin-bottom:2px;">
                                Scan in at the entrance
                            </strong>
                            <span style="font-size:12px; color:rgba(255,255,255,.45);">
                                Kiosk or tablet scans and confirms identity in under a second, logging the check-in time
                            </span>
                        </div>
                    </div>

                    <div class="flow-step">
                        <div class="step-icon amber">
                            <i class="ti ti-scan" style="color:#fbbf24;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <strong style="display:block; font-size:14px; font-weight:600; color:rgba(255,255,255,.9); margin-bottom:2px;">
                                Scan out on departure
                            </strong>
                            <span style="font-size:12px; color:rgba(255,255,255,.45);">
                                Same QR code used at exit — departure time is captured and duration is calculated automatically
                            </span>
                        </div>
                    </div>

                    <div class="flow-step">
                        <div class="step-icon green">
                            <i class="ti ti-chart-bar" style="color:#34d399;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <strong style="display:block; font-size:14px; font-weight:600; color:rgba(255,255,255,.9); margin-bottom:2px;">
                                Records synced instantly
                            </strong>
                            <span style="font-size:12px; color:rgba(255,255,255,.45);">
                                HR dashboard updates in real time — timestamps, durations, late flags, and early departures
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Live dashboard preview ── --}}
        <div id="dashboard" class="section">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
                <div>
                    <p class="section-label">Live dashboard</p>
                    <p style="font-size:16px; font-weight:600;">Today's attendance — Staff &amp; Attachees</p>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <div class="pulse-dot"></div>
                    <span style="font-size:12px; color:rgba(255,255,255,.4);">{{ now()->format('l, M j') }} · Live</span>
                </div>
            </div>

            <div class="dash-metrics">
                <div class="metric-card">
                    <div class="metric-label">Checked in</div>
                    <div class="metric-val">184</div>
                    <div class="metric-up">↑ 12% vs yesterday</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Checked out</div>
                    <div class="metric-val">47</div>
                    <div class="metric-up">Departures logged</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Late arrivals</div>
                    <div class="metric-val">13</div>
                    <div class="metric-down">7.1% of total</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label">Absent today</div>
                    <div class="metric-val">22</div>
                    <div class="metric-down">↑ 3 vs yesterday</div>
                </div>
            </div>

            <div class="bar-area">
                <div class="bar-label">Weekly check-ins (staff + attachees)</div>
                <div class="bars" id="bars"></div>
            </div>

            <p style="font-size:12px; color:rgba(255,255,255,.4); margin-bottom:10px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">
                Recent activity
            </p>

            <div class="recent-checkins">
                <div class="checkin-row">
                    <div class="avatar" style="background:rgba(124,58,237,.2); color:#a78bfa;">WN</div>
                    <div style="flex:1;">
                        <div class="checkin-name">Wanjiku Njoroge</div>
                        <div class="checkin-dept">Engineering · Staff</div>
                    </div>
                    <span class="checkin-time">08:47 AM</span>
                    <span class="badge badge-green">Checked in</span>
                </div>

                <div class="checkin-row">
                    <div class="avatar" style="background:rgba(14,165,233,.2); color:#38bdf8;">AO</div>
                    <div style="flex:1;">
                        <div class="checkin-name">Amara Osei</div>
                        <div class="checkin-dept">Product · Attachee</div>
                    </div>
                    <span class="checkin-time">08:52 AM</span>
                    <span class="badge badge-green">Checked in</span>
                </div>

                <div class="checkin-row">
                    <div class="avatar" style="background:rgba(251,191,36,.15); color:#fbbf24;">KM</div>
                    <div style="flex:1;">
                        <div class="checkin-name">Kipchoge Mutai</div>
                        <div class="checkin-dept">Operations · Staff</div>
                    </div>
                    <span class="checkin-time">09:18 AM</span>
                    <span class="badge badge-amber">Late</span>
                </div>

                <div class="checkin-row">
                    <div class="avatar" style="background:rgba(52,211,153,.15); color:#34d399;">FN</div>
                    <div style="flex:1;">
                        <div class="checkin-name">Fatima Ndoye</div>
                        <div class="checkin-dept">HR · Staff</div>
                    </div>
                    <span class="checkin-time">12:31 PM</span>
                    <span class="badge badge-purple">Checked out</span>
                </div>
            </div>
        </div>

        {{-- ── Features ── --}}
        <div id="features" class="section" style="padding-top:0;">
            <p class="section-label">Features</p>
            <h2 class="section-title">Everything HR needs,<br>nothing they don't</h2>
            <p class="section-sub">Built for offices managing both permanent staff and attachees — full in/out tracking in one place.</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(124,58,237,.18);">
                        <i class="ti ti-qrcode" style="color:#a78bfa; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Unique QR per person</div>
                    <div class="feat-desc">Every staff member and attachee gets a tamper-proof QR code linked to their profile and role.</div>
                </div>

                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(14,165,233,.15);">
                        <i class="ti ti-login" style="color:#38bdf8; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Check-in &amp; check-out</div>
                    <div class="feat-desc">The same QR code handles both entry and exit — arrival time, departure time, and total hours on site are all captured automatically.</div>
                </div>

                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(52,211,153,.12);">
                        <i class="ti ti-report-analytics" style="color:#34d399; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Real-time HR dashboard</div>
                    <div class="feat-desc">Live view of who's in, who's out, who's late, and who's absent — updated with every scan.</div>
                </div>

                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(167,139,250,.12);">
                        <i class="ti ti-users-group" style="color:#c4b5fd; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Staff &amp; attachee separation</div>
                    <div class="feat-desc">Filter and report independently by user type — view permanent staff and attachees in one unified system or separately.</div>
                </div>

                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(251,191,36,.12);">
                        <i class="ti ti-bell-ringing" style="color:#fbbf24; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Late &amp; absence alerts</div>
                    <div class="feat-desc">Auto-notify supervisors when a staff member or attachee hasn't checked in by the designated cutoff time.</div>
                </div>

                <div class="feature-card">
                    <div class="feat-icon" style="background:rgba(248,113,113,.12);">
                        <i class="ti ti-file-export" style="color:#f87171; font-size:22px;" aria-hidden="true"></i>
                    </div>
                    <div class="feat-title">Export &amp; payroll-ready</div>
                    <div class="feat-desc">One-click CSV or PDF export by date range, department, user type, or individual — ready for payroll and reporting.</div>
                </div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <footer>
            <a href="{{ url('/') }}" class="nav-logo" style="gap:8px;">
                <div class="logo-icon" style="width:30px; height:30px; border-radius:8px;">
                    <i class="ti ti-scan" style="color:#fff; font-size:14px;" aria-hidden="true"></i>
                </div>
                <span class="logo-text" style="font-size:15px;">Lish AI Labs</span>
            </a>

            <div class="footer-links">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Docs</a>
                <a href="#">Status</a>
                <a href="#">Contact</a>
            </div>

            <span class="footer-copy">© {{ date('Y') }} Lish AI Labs. All rights reserved.</span>
        </footer>

    </div>{{-- .content --}}

    <script>
        // QR pattern
        const qp = document.getElementById('qrpat');
        if (qp) {
            const bits = [
                1,1,1,0,1,1,1,
                1,0,1,0,1,0,1,
                1,1,1,0,0,1,0,
                0,0,0,1,1,0,1,
                0,1,1,0,1,1,1,
                1,0,0,0,0,0,1,
                1,1,0,1,1,1,1
            ];
            bits.forEach(b => {
                const d = document.createElement('div');
                d.className = 'qr-bit ' + (b ? 'on' : 'off');
                qp.appendChild(d);
            });
        }

        // Weekly bar chart
        const bc = document.getElementById('bars');
        if (bc) {
            const days = ['Mon','Tue','Wed','Thu','Fri'];
            const vals = [162, 175, 190, 184, 0];
            const max  = 195;
            days.forEach((d, i) => {
                const col = document.createElement('div');
                col.className = 'bar-col';
                const pct = vals[i] ? Math.round((vals[i] / max) * 100) : 0;
                const bg  = i === 4
                    ? 'rgba(255,255,255,0.08)'
                    : 'linear-gradient(180deg,#a78bfa,#4f46e5)';
                col.innerHTML = `
                    <div class="bar-fill" style="height:${pct}%; background:${bg};"></div>
                    <span class="bar-day">${d}</span>
                `;
                bc.appendChild(col);
            });
        }
    </script>
</body>
</html>