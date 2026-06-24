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
        min-height: 200px;
        min-width: 200px;
    }

    #qrcode {
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }

    #qrcode canvas,
    #qrcode img {
        width: 100%;
        height: 100%;
        display: block;
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

    .token-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .token-text {
        flex: 1;
        font-size: 14px;
        font-weight: 600;
        color: #a78bfa;
        word-break: break-all;
        font-family: 'Courier New', monospace;
    }

    .btn-copy {
        background: rgba(124,58,237,.15);
        border: 1px solid rgba(124,58,237,.3);
        color: #a78bfa;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-copy:hover {
        background: rgba(124,58,237,.25);
        border-color: rgba(124,58,237,.5);
    }

    .btn-copy.copied {
        background: rgba(52,211,153,.15);
        border-color: rgba(52,211,153,.3);
        color: #34d399;
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

    .error-message {
        color: #f87171;
        padding: 20px;
        background: rgba(248,113,113,.1);
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>

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
            <div class="token-label">Today's QR Token</div>
            <div class="token-wrapper">
                <div class="token-text" id="tokenText">{{ $qrToken }}</div>
                <button onclick="copyToken()" class="btn-copy" id="copyBtn">
                    <i class="ti ti-copy" id="copyIcon"></i>
                    <span id="copyText">Copy</span>
                </button>
            </div>
        </div>

        <div style="background: rgba(52,211,153,.1); border: 1px solid rgba(52,211,153,.3); border-radius: 12px; padding: 16px; margin-bottom: 24px; text-align: left;">
            <div style="font-size: 13px; color: #34d399; line-height: 1.6;">
                <strong>How staff can mark attendance:</strong><br>
                1. Scan this QR code with their phone camera<br>
                2. Or manually enter this token on their attendance page
            </div>
        </div>

        <button onclick="downloadQRCode()" class="btn-refresh">
            <i class="ti ti-download"></i>
            Download QR Code
        </button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const qrToken = '{{ $qrToken }}';
    let qrCodeObj = null;

    function generateQRCode() {
        console.log('Generating QR code with token:', qrToken);
        
        const qrContainer = document.getElementById('qrcode');
        qrContainer.innerHTML = '';
        
        if (!qrToken || qrToken === '') {
            qrContainer.innerHTML = '<div class="error-message">No QR token available</div>';
            return;
        }
        
        if (typeof QRCode === 'undefined') {
            qrContainer.innerHTML = '<div class="error-message">QR Code library not loaded. Please refresh the page.</div>';
            console.error('QRCode library not loaded');
            return;
        }
        
        try {
            qrCodeObj = new QRCode(qrContainer, {
                text: qrToken,
                width: 200,
                height: 200,
                colorDark: "#7c3aed",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            console.log('QR Code generated successfully');
        } catch (e) {
            console.error('QR Code generation exception:', e);
            qrContainer.innerHTML = '<div class="error-message">Error generating QR code: ' + e.message + '</div>';
        }
    }

    function downloadQRCode() {
        const qrContainer = document.getElementById('qrcode');
        const img = qrContainer.querySelector('img');

        if (!img) {
            alert('Please generate the QR code first');
            return;
        }

        const link = document.createElement('a');
        link.download = 'qr-code-{{ $today }}.png';
        link.href = img.src;
        link.click();
    }

    function copyToken() {
        const tokenText = document.getElementById('tokenText').textContent;
        const copyBtn = document.getElementById('copyBtn');
        const copyIcon = document.getElementById('copyIcon');
        const copyText = document.getElementById('copyText');

        navigator.clipboard.writeText(tokenText).then(() => {
            copyBtn.classList.add('copied');
            copyIcon.className = 'ti ti-check';
            copyText.textContent = 'Copied!';

            setTimeout(() => {
                copyBtn.classList.remove('copied');
                copyIcon.className = 'ti ti-copy';
                copyText.textContent = 'Copy';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy token:', err);
            alert('Failed to copy token. Please try again.');
        });
    }

    // Generate QR code when library is loaded
    function initQRCode() {
        if (typeof QRCode !== 'undefined') {
            console.log('QRCode library loaded');
            generateQRCode();
        } else {
            console.log('Waiting for QRCode library...');
            setTimeout(initQRCode, 100);
        }
    }

    // Start initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQRCode);
    } else {
        initQRCode();
    }
</script>
@endsection
