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

    .pending-wrapper {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 500px;
        padding: 24px 16px;
    }

    .pending-card {
        background: rgba(255,255,255,.04);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 24px;
        padding: 48px 40px;
        text-align: center;
    }

    .icon-wrap {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(124,58,237,.2), rgba(14,165,233,.2));
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(124,58,237,.3);
    }

    .icon-wrap i {
        font-size: 36px;
        color: #a78bfa;
    }

    .card-title {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }

    .card-sub {
        font-size: 14px;
        color: rgba(255,255,255,.5);
        line-height: 1.6;
        margin-bottom: 32px;
    }

    .info-box {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
    }

    .info-item:first-child { padding-top: 0; }
    .info-item:last-child { padding-bottom: 0; border-top: 1px solid rgba(255,255,255,.08); margin-top: 8px; }

    .info-label {
        color: rgba(255,255,255,.4);
    }

    .info-value {
        color: #fff;
        font-weight: 600;
    }

    .btn-logout {
        width: 100%;
        padding: 13px;
        background: rgba(255,255,255,.08);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        font-family: inherit;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 10px;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-logout:hover {
        background: rgba(255,255,255,.12);
        border-color: rgba(255,255,255,.25);
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/dist/tabler-icons.min.css">

<div class="mesh-bg"></div>
<div class="grid-overlay"></div>

<div class="pending-wrapper">
    <div class="pending-card">
        <div class="icon-wrap">
            <i class="ti ti-clock"></i>
        </div>
        <div class="card-title">Account Pending Approval</div>
        <div class="card-sub">
            Your account is awaiting administrator approval. You will gain access once your account has been reviewed and approved.
        </div>

        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Name</span>
                <span class="info-value">{{ auth()->user()->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ auth()->user()->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Role</span>
                <span class="info-value">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value" style="color: #fbbf24;">{{ ucfirst(auth()->user()->status) }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="ti ti-logout"></i>
                Sign Out
            </button>
        </form>
    </div>
</div>

</x-guest-layout>
