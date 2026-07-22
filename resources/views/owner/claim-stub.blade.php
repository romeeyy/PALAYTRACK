@extends('layouts.owner')

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
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #111827;
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
        padding: 28px 36px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .stub-header {
        text-align: center;
        margin-bottom: 22px;
    }

    .stub-title {
        font-size: 2rem;
        font-weight: 800;
        color: #2f5d1e;
        margin: 0;
        line-height: 1.1;
    }

    .stub-subtitle {
        font-size: 1.1rem;
        color: #334155;
        margin-top: 8px;
        font-weight: 600;
    }

    .stub-divider {
        border-top: 2px solid #2f5d1e;
        margin: 18px 0;
    }

    .stub-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 2px;
        font-weight: 600;
    }

    .stub-value {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.5;
    }

    .estimate-box {
        border: 2px solid #16a34a;
        border-radius: 12px;
        padding: 14px 18px;
        background: #f0fdf4;
        margin-top: 12px;
    }

    .estimate-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #15803d;
        line-height: 1.1;
    }

    .important-box {
        margin-top: 18px;
        border: 2px dashed #365314;
        background: #fefce8;
        border-radius: 14px;
        padding: 14px 16px;
        text-align: center;
    }

    .important-box h5 {
        margin-bottom: 10px;
        font-size: 1rem;
        font-weight: 800;
        color: #365314;
    }

    .important-box p {
        margin-bottom: 4px;
        color: #334155;
    }

    .signature-area {
        margin-top: 26px;
    }

    .signature-line {
        border-top: 2px solid #9ca3af;
        margin-top: 28px;
    }

    .signature-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 6px;
        line-height: 1.4;
    }

    .footer-text {
        text-align: center;
        margin-top: 22px;
        color: #64748b;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .stub-grid-gap {
        margin-bottom: 18px;
    }

    @media (max-width: 768px) {
        .stub-card {
            padding: 22px 18px;
        }

        .stub-title {
            font-size: 1.75rem;
        }

        .stub-subtitle {
            font-size: 1rem;
        }

        .estimate-value {
            font-size: 1.35rem;
        }
    }

    @page {
        size: A4 portrait;
        margin: 12mm;
    }

    @media print {
        html,
        body {
            background: white !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .sidebar,
        .topbar,
        .stub-topbar,
        .btn,
        .no-print {
            display: none !important;
        }

        .main-content,
        .content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .app-wrapper {
            display: block !important;
            min-height: 0 !important;
        }

        .stub-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .stub-card {
            width: 100% !important;
            margin: 0 auto !important;
            padding: 18px 24px !important;
            box-shadow: none !important;
            border: 3px solid #2f5d1e !important;
            border-radius: 14px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .stub-header {
            margin-bottom: 14px !important;
        }

        .stub-title {
            font-size: 28px !important;
        }

        .stub-subtitle {
            font-size: 16px !important;
            margin-top: 4px !important;
        }

        .stub-divider {
            margin: 12px 0 !important;
        }

        .stub-label {
            font-size: 12px !important;
        }

        .stub-value {
            font-size: 15px !important;
        }

        .estimate-box {
            padding: 10px 14px !important;
            margin-top: 10px !important;
        }

        .estimate-value {
            font-size: 22px !important;
        }

        .important-box {
            padding: 10px 12px !important;
            margin-top: 14px !important;
        }

        .important-box h5 {
            font-size: 14px !important;
            margin-bottom: 6px !important;
        }

        .important-box p,
        .text-muted {
            font-size: 12px !important;
            line-height: 1.35 !important;
        }

        .signature-area {
            margin-top: 18px !important;
        }

        .signature-line {
            margin-top: 20px !important;
        }

        .signature-label {
            font-size: 11px !important;
        }

        .footer-text {
            margin-top: 14px !important;
            font-size: 11px !important;
            line-height: 1.35 !important;
        }

        .row.mb-4,
        .stub-grid-gap {
            margin-bottom: 12px !important;
        }

        @page {
            size: A4 portrait;
            margin: 6mm;
        }
    }
</style>

<div class="stub-topbar d-flex justify-content-between align-items-center no-print flex-wrap gap-3">
    <a href="/owner/delivery-details/{{ $delivery->id }}" class="back-btn">
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

        <div class="stub-header d-flex align-items-center justify-content-center gap-3">
            <i data-lucide="wheat" style="width:42px;height:42px;color:#2f5d1e"></i>

            <div>
                <h1 class="stub-title">PalayTrack</h1>
                <div class="stub-subtitle">Claim Stub</div>
            </div>
        </div>

        <div class="stub-divider"></div>

        <div class="row stub-grid-gap">
            <div class="col-6">
                <div class="stub-label">Delivery ID</div>
                <div class="stub-value">{{ $delivery->delivery_id }}</div>
            </div>

            <div class="col-6">
                <div class="stub-label">Daily Queue Number</div>
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
        </div>

        <div class="stub-divider"></div>

        <div class="mb-2">
            <div class="stub-label">Rice Type / Variety</div>
            <div class="stub-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
        </div>

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
    window.addEventListener('load', function () {
        window.print();
    });
</script>
@endif
