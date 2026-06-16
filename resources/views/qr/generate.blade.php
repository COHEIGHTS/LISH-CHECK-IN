@extends('layouts.app-dashboard')

@section('content')
@php $title = 'QR Code Generation'; @endphp

<style>
    .qr-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }

    .qr-card {
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

    .qr-display {
        background: rgba(255,255,255,.06);
        border: 2px solid rgba(124,58,237,.3);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        display: inline-block;
    }

    #qrcode {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }

    #qrcode img {
        width: 100%;
        height: 100%;
    }

    .token-display {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .token-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .token-value {
        font-size: 14px;
        font-weight: 600;
        color: #a78bfa;
        word-break: break-all;
        font-family: 'Courier New', monospace;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .info-item {
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 12px;
        padding: 16px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.5);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .btn-refresh {
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

    .btn-refresh:hover { opacity: .9; }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(52,211,153,.15);
        color: #34d399;
        margin-bottom: 16px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<div class="qr-wrapper">
    <div class="qr-card">
        <div class="header">
            <div class="status-badge">Active Today</div>
            <h2>Daily QR Code</h2>
            <p>Generate QR code for today's attendance check-in</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Date</div>
                <div class="info-value">{{ $today }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">Active</div>
            </div>
        </div>

        <div class="qr-display">
            <div id="qrcode"></div>
        </div>

        <div class="token-display">
            <div class="token-label">QR Token</div>
            <div class="token-value">{{ $qrToken }}</div>
        </div>

        <button onclick="generateQRCode()" class="btn-refresh">
            <i class="ti ti-refresh"></i>
            Regenerate QR Code
        </button>

        <button onclick="downloadQRCode()" class="btn-refresh" style="margin-left: 8px;">
            <i class="ti ti-download"></i>
            Download QR Code
        </button>
    </div>
</div>

<script>
    const qrToken = '{{ $qrToken }}';
    let qrCanvas = null;

    function generateQRCode() {
        const qrContainer = document.getElementById('qrcode');
        qrContainer.innerHTML = '';
        
        QRCode.toCanvas(qrToken, { 
            width: 200,
            margin: 2,
            color: {
                dark: '#7c3aed',
                light: '#ffffff'
            }
        }, function(error, canvas) {
            if (error) {
                console.error(error);
                qrContainer.innerHTML = '<div style="color: #f87171; padding: 20px;">Error generating QR code</div>';
                return;
            }
            qrCanvas = canvas;
            qrContainer.appendChild(canvas);
        });
    }

    function downloadQRCode() {
        if (!qrCanvas) {
            alert('Please generate the QR code first');
            return;
        }
        
        const link = document.createElement('a');
        link.download = 'qr-code-{{ $today }}.png';
        link.href = qrCanvas.toDataURL('image/png');
        link.click();
    }

    // Generate QR code on page load
    document.addEventListener('DOMContentLoaded', generateQRCode);
</script>
@endsection
