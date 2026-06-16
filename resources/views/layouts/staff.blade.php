<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Staff Dashboard - Lish AI Labs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #06061a;
            color: #ffffff;
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

        .glass {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.09);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            transition: all .2s;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(14,165,233,.15);
            color: #38bdf8;
            border: 1px solid rgba(14,165,233,.3);
        }

        .sidebar-link i {
            font-size: 20px;
        }

        .nav-link {
            color: rgba(255,255,255,.7);
            text-decoration: none;
            transition: color .2s;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #38bdf8;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s;
            text-decoration: none;
        }

        .btn-primary:hover { opacity: .9; }
    </style>
</head>
<body>
    <div class="mesh-bg"></div>
    <div class="grid-overlay"></div>

    <div class="flex h-screen relative z-10">
        {{-- Sidebar --}}
        <aside class="w-64 glass flex flex-col h-full fixed left-0 top-0">
            {{-- Logo --}}
            <div class="p-6 border-b border-white/10">
                <a href="{{ route('dashboard.staff') }}" class="flex items-center gap-3 text-decoration-none">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                        <i class="ti ti-scan text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="font-bold text-lg bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">Lish AI Labs</div>
                        <div class="text-xs text-white/50">Staff Panel</div>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 px-3">Main</div>
                
                <a href="{{ route('dashboard.staff') }}" class="sidebar-link {{ request()->is('dashboard/staff') ? 'active' : '' }}">
                    <i class="ti ti-layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('qr.scan') }}" class="sidebar-link {{ request()->is('qr/scan') ? 'active' : '' }}">
                    <i class="ti ti-qr-code"></i>
                    <span>Scan QR Code</span>
                </a>

                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 px-3 mt-6">Attendance</div>

                <a href="#" class="sidebar-link">
                    <i class="ti ti-calendar"></i>
                    <span>My Attendance</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="ti ti-history"></i>
                    <span>History</span>
                </a>

                <div class="text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 px-3 mt-6">Settings</div>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->is('profile') ? 'active' : '' }}">
                    <i class="ti ti-user"></i>
                    <span>My Profile</span>
                </a>
            </nav>

            {{-- User Info --}}
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                        <span class="text-white font-semibold">{{ auth()->user()->name[0] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-sm truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white transition-all text-sm font-medium">
                        <i class="ti ti-logout"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 ml-64 flex flex-col h-full">
            {{-- Top Navigation --}}
            <header class="glass h-16 flex items-center justify-between px-6 border-b border-white/10">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold">{{ isset($title) ? $title : 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    <button class="relative p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <i class="ti ti-bell text-xl text-white/70"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- Quick Actions --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('qr.scan') }}" class="btn-primary flex items-center gap-2">
                            <i class="ti ti-qr-code"></i>
                            <span>Scan QR</span>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
