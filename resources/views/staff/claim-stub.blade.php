@extends('layouts.staff')

@section('content')

<style>
    .stub-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    .stub-topbar {
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #ffffff;
        color: #111827;
        text-decoration: none;
        font-weight: 600;
    }

    .print-btn {
        min-height: 44px;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(47, 93, 30, 0.16);
    }

    .stub-card {
        background: #ffffff;
        border: 4px solid #2f5d1e;
        border-radius: 18px;
        padding: 34px 42px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        min-height: 92vh;
    }

    .stub-header {
        display: grid !important;
        grid-template-columns: 130px 1fr 130px;
        align-items: center;
        text-align: center;
        margin-bottom: 26px;
    }

    .stub-logo {
        width: 115px;
        height: 115px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #2f5d1e;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-left: 18px;
    }

    .stub-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .stub-header-text {
        text-align: center;
    }

    .stub-company-name {
        font-size: 1.1rem;
        font-weight: 900;
        letter-spacing: 0.08em;
        color: #2f5d1e;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .stub-title {
        font-size: 2.55rem;
        font-weight: 900;
        color: #2f5d1e;
        margin: 0;
        line-height: 1.05;
    }

    .stub-subtitle {
        font-size: 1.35rem;
        color: #334155;
        margin-top: 8px;
        font-weight: 800;
    }

    .stub-divider {
        border-top: 2px solid #2f5d1e;
        margin: 20px 0;
    }

    .stub-label {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 3px;
        font-weight: 700;
    }

    .stub-value {
        font-size: 1.12rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.55;
    }

    .estimate-box {
        border: 2px solid #16a34a;
        border-radius: 12px;
        padding: 16px 20px;
        background: #f0fdf4;
        margin-top: 14px;
    }

    .estimate-value {
        font-size: 1.85rem;
        font-weight: 900;
        color: #15803d;
        line-height: 1.1;
    }

    .important-box {
        margin-top: 24px;
        border: 2px dashed #365314;
        background: #fefce8;
        border-radius: 14px;
        padding: 18px 18px;
        text-align: center;
    }

    .important-box h5 {
        margin-bottom: 10px;
        font-size: 1.08rem;
        font-weight: 900;
        color: #365314;
    }

    .important-box p {
        margin-bottom: 6px;
        color: #334155;
        font-size: 1rem;
    }

    .signature-area {
        margin-top: 48px;
    }

    .signature-line {
        border-top: 2px solid #9ca3af;
        margin-top: 28px;
    }

    .signature-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 6px;
        line-height: 1.4;
    }

    .footer-text {
        text-align: center;
        margin-top: 30px;
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .stub-grid-gap {
        margin-bottom: 20px;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .sidebar,
        .topbar,
        .stub-topbar,
        .btn,
        .no-print {
            display: none !important;
        }

        .app-wrapper {
            display: block !important;
        }

        .main-content,
        .content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .stub-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .stub-card {
            width: 100% !important;
            min-height: 96vh !important;
            margin: 0 auto !important;
            padding: 28px 34px !important;
            box-shadow: none !important;
            border: 3px solid #2f5d1e !important;
            border-radius: 14px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .stub-header {
            margin-bottom: 18px !important;
            grid-template-columns: 130px 1fr 130px !important;
        }

        .stub-logo {
            width: 110px !important;
            height: 110px !important;
            margin-left: 18px !important;
        }

        .stub-company-name {
            font-size: 16px !important;
            color: #2f5d1e !important;
        }

        .stub-title {
            font-size: 34px !important;
            color: #2f5d1e !important;
        }

        .stub-subtitle {
            font-size: 18px !important;
            margin-top: 4px !important;
        }

        .stub-divider {
            margin: 16px 0 !important;
            border-top: 2px solid #2f5d1e !important;
        }

        .stub-label {
            font-size: 13px !important;
        }

        .stub-value {
            font-size: 16px !important;
        }

        .estimate-box {
            padding: 14px 18px !important;
            margin-top: 14px !important;
            background: #f0fdf4 !important;
            border: 2px solid #16a34a !important;
        }

        .estimate-value {
            font-size: 28px !important;
            color: #15803d !important;
        }

        .important-box {
            padding: 14px 16px !important;
            margin-top: 22px !important;
            background: #fefce8 !important;
            border: 2px dashed #365314 !important;
        }

        .important-box h5 {
            font-size: 16px !important;
            margin-bottom: 8px !important;
            color: #365314 !important;
        }

        .important-box p,
        .text-muted {
            font-size: 13px !important;
            line-height: 1.35 !important;
        }

        .signature-area {
            margin-top: 58px !important;
        }

        .signature-line {
            margin-top: 22px !important;
        }

        .signature-label {
            font-size: 12px !important;
        }

        .footer-text {
            margin-top: 36px !important;
            font-size: 12px !important;
            line-height: 1.35 !important;
        }

        .stub-grid-gap {
            margin-bottom: 16px !important;
        }
    }
</style>

<div class="stub-topbar d-flex justify-content-between align-items-center no-print flex-wrap gap-3">
    <a href="/staff/delivery-details/{{ $delivery->id }}" class="back-btn">
        <i data-lucide="arrow-left"></i>
        <span>Back</span>
    </a>

    <button onclick="window.print()" class="btn btn-success print-btn d-flex align-items-center gap-2">
        <i data-lucide="printer"></i>
        Print Claim Stub
    </button>
</div>

<div class="stub-wrapper">
    <div class="stub-card">

        <div class="stub-header">
            <div class="stub-logo">
                <img src="{{ asset('images/jk-logo.png') }}" alt="JK Diez Rice Mill Logo">
            </div>

            <div class="stub-header-text">
                <div class="stub-company-name">JK DIEZ RICE MILL</div>
                <h1 class="stub-title">PalayTrack</h1>
                <div class="stub-subtitle">Claim Stub</div>
            </div>

            <div></div>
        </div>

        <div class="stub-divider"></div>

        <div class="row stub-grid-gap">
            <div class="col-6">
                <div class="stub-label">Delivery ID</div>
                <div class="stub-value">{{ $delivery->delivery_id }}</div>
            </div>

            <div class="col-6">
                <div class="stub-label">Queue Number</div>
                <div class="stub-value">#{{ $delivery->queue_number }}</div>
            </div>
        </div>

        <div class="row stub-grid-gap">
            <div class="col-6">
                <div class="stub-label">Client / Palay Owner Name</div>
                <div class="stub-value">{{ $delivery->client_name }}</div>
            </div>

            <div class="col-6">
                <div class="stub-label">Contact Number</div>
                <div class="stub-value">{{ $delivery->contact_number }}</div>
            </div>
        </div>

        <div class="row stub-grid-gap">
            <div class="col-6">
                <div class="stub-label">Delivery Date</div>
                <div class="stub-value">
                    {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('m/d/Y') : 'N/A' }}
                </div>
            </div>

            <div class="col-6">
                <div class="stub-label">Rice Type / Variety</div>
                <div class="stub-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="stub-divider"></div>

        <div class="row mt-3 stub-grid-gap">
            <div class="col-4">
                <div class="stub-label">Number of Sacks</div>
                <div class="stub-value">{{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }}</div>
            </div>

            <div class="col-4">
                <div class="stub-label">Total Weight (kg)</div>
                <div class="stub-value">{{ number_format($delivery->palay_weight, 2) }}</div>
            </div>

            <div class="col-4">
                <div class="stub-label">Recovery Rate</div>
                <div class="stub-value">{{ number_format($delivery->recovery_rate, 2) }}%</div>
            </div>
        </div>

        <div class="estimate-box">
            <div class="stub-label">Estimated Milled Rice (kg)</div>
            <div class="estimate-value">{{ number_format($delivery->estimated_rice, 2) }} kg</div>
        </div>

        <div class="stub-divider"></div>

        <div class="stub-label">Notes</div>
        <div class="stub-value">{{ $delivery->notes ?: 'No notes provided.' }}</div>

        <div class="important-box">
            <h5 class="d-flex align-items-center justify-content-center gap-2">
                <i data-lucide="alert-triangle" style="width:18px;height:18px;"></i>
                IMPORTANT
            </h5>

            <p>Present this stub upon claiming your milled rice.</p>
            <p class="text-muted">Keep this stub safe. You will be notified when your order is ready.</p>
        </div>

        <div class="signature-area">
            <div class="row">
                <div class="col-6">
                    <div class="signature-line"></div>
                    <div class="signature-label">Received by (Customer Signature)</div>
                    <div class="signature-label">Signature over printed name</div>
                </div>

                <div class="col-6">
                    <div class="signature-line"></div>
                    <div class="signature-label">Processed by (Staff Signature)</div>
                    <div class="signature-label">Staff name and signature</div>
                </div>
            </div>
        </div>

        <div class="footer-text">
            This is an official delivery receipt from PalayTrack Rice Mill Management System
            <br>
            Generated on {{ now()->format('n/j/Y, g:i A') }}
        </div>

    </div>
</div>

@endsection

@if(request('print') == 1)
<script>
    window.addEventListener('load', function() {
        window.print();
    });
</script>
@endif
