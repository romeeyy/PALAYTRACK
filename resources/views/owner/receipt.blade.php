@extends('layouts.owner')

@section('content')

<style>
    .receipt-page {
        min-height: calc(100vh - 110px);
        background: #ffffff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
    }

    .receipt-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .receipt-page-title {
        font-size: 1.45rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
    }

    .receipt-page-subtitle {
        color: #64748b;
        margin: 4px 0 0;
        font-size: 0.95rem;
    }

    .receipt-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .receipt-actions .btn {
        border-radius: 14px;
        font-weight: 800;
        padding: 10px 18px;
        min-height: 44px;
    }

    .receipt-preview-area {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 18px 0 8px;
    }

    .thermal-wrapper {
        width: 80mm;
        max-width: 80mm;
        margin: 0 auto;
        background: #ffffff;
        padding: 16px;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        font-family: "Courier New", monospace;
    }

    .thermal-receipt {
        width: 100%;
        padding: 12px;
        border: 1px dashed #9ca3af;
        background: #fff;
        color: #000;
    }

    .center {
        text-align: center;
    }

    .mill-name {
        font-size: 19px;
        font-weight: 900;
        line-height: 1.2;
        margin: 0;
    }

    .receipt-title {
        font-size: 12px;
        font-weight: 700;
        margin: 2px 0 6px;
    }

    .receipt-address {
        font-size: 10px;
        line-height: 1.4;
    }

    .line {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    .row-line {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        font-size: 12px;
        line-height: 1.45;
    }

    .row-line span:first-child {
        text-align: left;
    }

    .row-line span:last-child {
        text-align: right;
        font-weight: 700;
        word-break: break-word;
    }

    .label {
        font-size: 11px;
        font-weight: 700;
        margin-top: 5px;
    }

    .value {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
        word-break: break-word;
    }

    .total-box {
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
        padding: 8px 0;
        margin: 8px 0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 15px;
        font-weight: 900;
    }

    .footer {
        font-size: 10px;
        text-align: center;
        margin-top: 10px;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .receipt-page {
            padding: 18px;
        }

        .receipt-page-header {
            align-items: flex-start;
        }

        .receipt-actions {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media print {

        @page {
            size: 80mm 210mm;
            margin: 3mm;
        }

        html,
        body {
            width: 74mm !important;
            min-width: 74mm !important;
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .sidebar,
        .topbar,
        .receipt-page-header,
        .btn,
        .no-print {
            display: none !important;
        }

        .app-wrapper,
        .main-content,
        .content,
        .receipt-page,
        .receipt-preview-area {
            display: block !important;
            width: 74mm !important;
            min-width: 74mm !important;
            max-width: 74mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .thermal-wrapper {
            width: 74mm !important;
            max-width: 74mm !important;
            transform: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: transparent !important;
        }

        .thermal-receipt {
            border: none !important;
            padding: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        * {
            color: #000 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
    }
</style>

<div class="receipt-page">

    <div class="receipt-page-header no-print">
        <div>
            <h1 class="receipt-page-title">Receipt Preview</h1>
            <p class="receipt-page-subtitle">
                Review and print the official payment receipt.
            </p>
        </div>

        <div class="receipt-actions">

            <a href="{{ route('owner.payment-records', ['date' => ($transaction->paid_at ?? $transaction->created_at)->toDateString()]) }}"
               class="btn btn-outline-secondary">
                &larr; Back to Transactions
            </a>

            @if($transaction->payment_proof_path)
            <a href="{{ asset('storage/' . $transaction->payment_proof_path) }}"
               class="btn btn-outline-success"
               target="_blank"
               rel="noopener">
                View Payment Proof
            </a>
            @endif

            <button onclick="window.print()" class="btn btn-success">
                Print Receipt
            </button>

        </div>
    </div>

    <div class="receipt-preview-area">

        <div class="thermal-wrapper">

            <div class="thermal-receipt">

                <div class="center">

                    <div class="mill-name">
                        JK DIEZ RICE MILL
                    </div>

                    <div class="receipt-title">
                        PAYMENT RECEIPT
                    </div>

                    <div class="receipt-address">
                        Rice Mill Management System
                    </div>

                </div>

                <div class="line"></div>

                <div class="row-line">
                    <span>Receipt No:</span>

                    <span>
                        TXN-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Delivery ID:</span>

                    <span>
                        {{ $delivery->delivery_id }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Paid At:</span>

                    <span>
                        {{ $transaction->paid_at ? $transaction->paid_at->format('m/d/Y g:i A') : 'N/A' }}
                    </span>
                </div>

                <div class="line"></div>

                <div class="label">
                    CLIENT NAME
                </div>

                <div class="value">
                    {{ $delivery->client_name }}
                </div>

                <div class="row-line">
                    <span>Rice Type:</span>

                    <span>
                        {{ $delivery->riceType->name ?? 'N/A' }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Payment:</span>

                    <span>
                        {{ strtoupper($transaction->payment_method) }}
                    </span>
                </div>

                @if($transaction->payment_method !== 'cash')

                <div class="row-line">
                    <span>Ref No:</span>

                    <span>
                        {{ $transaction->reference_number ?: '-' }}
                    </span>
                </div>

                @endif

                <div class="line"></div>

                <div class="row-line">
                    <span>Palay Weight</span>
                    <span>{{ number_format($transaction->palay_weight_kg, 2) }} kg</span>
                </div>

                <div class="row-line">
                    <span>Fee / kg</span>

                    <span>
                        PHP {{ number_format($transaction->milling_fee_per_kg, 2) }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Subtotal</span>

                    <span>
                        PHP {{ number_format($transaction->subtotal, 2) }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Other Charges</span>

                    <span>
                        PHP {{ number_format($transaction->other_charges, 2) }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Discount</span>

                    <span>
                        PHP {{ number_format($transaction->discount, 2) }}
                    </span>
                </div>

                @if($transaction->notes)
                <div class="row-line">
                    <span>Adjustment Reason</span>
                    <span>{{ $transaction->notes }}</span>
                </div>
                @endif

                <div class="total-box">

                    <div class="total-row">

                        <span>TOTAL</span>

                        <span>
                            PHP {{ number_format($transaction->total_amount, 2) }}
                        </span>

                    </div>

                </div>

                <div class="row-line">
                    <span>Amount Paid</span>

                    <span>
                        PHP {{ number_format($transaction->amount_received, 2) }}
                    </span>
                </div>

                <div class="row-line">
                    <span>Change</span>

                    <span>
                        PHP {{ number_format($transaction->change_amount, 2) }}
                    </span>
                </div>

                <div class="line"></div>

                <div class="label">
                    PROCESSED BY
                </div>

                <div class="value">
                    {{ optional($transaction->user)->name ?? 'Staff' }}
                </div>

                <div class="line"></div>

                <div class="label">
                    NOTES
                </div>

                <div class="value">
                    {{ $transaction->notes ?: 'No notes provided.' }}
                </div>

                <div class="line"></div>

                <div class="footer">
                    This serves as proof of payment.<br>
                    Thank you for your transaction.<br><br>
                    Receipt Generated:<br>
                    {{ $transaction->paid_at ? $transaction->paid_at->format('m/d/Y g:i A') : $transaction->created_at->format('m/d/Y g:i A') }}
                </div>

            </div>

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
