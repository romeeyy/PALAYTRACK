@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 5px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .btn-print {
        background: #2f5d1e;
        color: #fff;
        border: none;
        padding: 12px 18px;
        border-radius: 12px;
        font-weight: 900;
        box-shadow: 0 10px 22px rgba(47, 93, 30, 0.18);
    }

    .report-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        margin-bottom: 18px;
    }


    .filter-card {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .field-label {
        font-weight: 900;
        color: #111827;
        font-size: 0.88rem;
        margin-bottom: 7px;
        display: block;
    }

    .custom-input {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        padding: 10px 13px;
        font-size: 0.92rem;
        width: 100%;
        background: #fff;
        font-weight: 700;
    }

    .btn-main {
        background: #2f5d1e;
        color: #fff;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 900;
        border: none;
        min-height: 48px;
    }

    .print-header {
        text-align: center;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 3px solid #2f5d1e;
    }

    .print-logo {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 8px;
    }

    .print-business {
        font-size: 1.6rem;
        font-weight: 900;
        color: #2f5d1e;
        margin: 0;
    }

    .print-title {
        font-size: 1rem;
        font-weight: 900;
        color: #0f172a;
        margin: 3px 0 0;
    }

    .report-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 18px;
    }

    .meta-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 0.9rem;
        color: #334155;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .collection-summary {
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .collection-summary-title {
        margin: 0 0 10px;
        font-size: 0.95rem;
        font-weight: 900;
        color: #334155;
    }

    .collection-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .collection-summary-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 11px 13px;
    }

    .collection-summary-item span {
        display: block;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .collection-summary-item strong {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 900;
    }

    .adjustment-line { display: block; }
    .adjustment-line + .adjustment-line { margin-top: 6px; }
    .adjustment-amount { display: block; color: #0f172a; font-weight: 900; }
    .adjustment-kind { display: block; margin-top: 2px; color: #64748b; font-size: 0.7rem; font-weight: 700; }
    .adjustment-charge,
    .adjustment-discount { color: #334155; }

    @media (max-width: 768px) {
        .collection-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px;
    }

    .summary-label {
        font-size: 0.78rem;
        font-weight: 900;
        color: #64748b;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 1.25rem;
        font-weight: 900;
        color: #0f172a;
    }

    .summary-green {
        color: #15803d;
    }

    .summary-orange {
        color: #ea580c;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .section-title {
        font-size: 1.18rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    .record-badge {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #bbf7d0;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 900;
    }

    .table-wrap {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow-x: auto;
        background: #ffffff;
    }

    .sales-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .sales-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.76rem;
        font-weight: 900;
        text-transform: uppercase;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .sales-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
        vertical-align: middle;
        white-space: nowrap;
        text-align: left !important;
    }

    .text-right {
        text-align: right;
    }

    .client-name {
        font-weight: 900;
        color: #0f172a;
    }

    .type-pill {
        display: inline-flex;
        padding: 6px 10px;
        border-radius: 999px;
        font-weight: 900;
        font-size: 0.75rem;
    }

    .type-menudo {
        background: #ecfdf5;
        color: #047857;
    }

    .type-commercial {
        background: #fff7ed;
        color: #c2410c;
    }

    .fee-text {
        font-weight: 900;
        color: #2563eb;
    }

    .amount-text {
        font-weight: 900;
        color: #0f172a;
    }

    .total-row td {
        background: #f8fafc;
        font-weight: 900;
        color: #0f172a;
        border-top: 2px solid #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 32px;
        color: #64748b;
        font-weight: 700;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 60px;
        margin-top: 45px;
    }

    .signature-box {
        text-align: center;
        padding-top: 35px;
    }

    .signature-line {
        border-top: 1px solid #111827;
        padding-top: 8px;
        font-weight: 900;
        color: #0f172a;
    }

    .signature-label {
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 3px;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body * {
            visibility: hidden;
        }

        #printArea,
        #printArea * {
            visibility: visible;
        }

        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white;
            color: #111827;
            font-family: Arial, sans-serif;
        }

        .no-print {
            display: none !important;
        }

        .report-card {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .print-header {
            margin-bottom: 12px;
            padding-bottom: 8px;
        }

        .print-logo {
            width: 58px;
            height: 58px;
            margin-bottom: 4px;
        }

        .print-business {
            font-size: 20px;
        }

        .print-title {
            font-size: 13px;
        }

        .report-meta {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .meta-item {
            display: table-row;
            border: none;
            background: transparent;
            padding: 0;
            font-size: 11px;
        }

        .meta-item strong {
            display: inline-block;
            min-width: 95px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .summary-card {
            display: table-cell;
            border: 1px solid #111827;
            border-radius: 0;
            padding: 6px 8px;
            width: 20%;
        }

        .summary-label {
            font-size: 9px;
            color: #111827;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 12px;
            color: #111827 !important;
        }

        .collection-summary {
            margin-top: 10px;
            padding-top: 8px;
            page-break-inside: avoid;
        }

        .collection-summary-title {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .collection-summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .collection-summary-item {
            display: table-cell;
            width: 25%;
            border: 1px solid #111827;
            border-radius: 0;
            background: transparent;
            padding: 5px 7px;
        }

        .collection-summary-item span {
            color: #111827;
            font-size: 8px;
        }

        .collection-summary-item strong {
            color: #111827;
            font-size: 10px;
        }

        .section-head {
            display: block;
            margin-bottom: 8px;
        }

        .section-title {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .section-subtitle {
            font-size: 10px;
            color: #374151;
        }

        .record-badge {
            display: none;
        }

        .table-wrap {
            border: none;
            border-radius: 0;
            overflow: visible !important;
        }

        .sales-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .sales-table thead th {
            background: #f3f4f6 !important;
            color: #111827;
            border: 1px solid #111827;
            padding: 6px 5px;
            font-size: 8.5px;
            white-space: normal;
        }

        .sales-table tbody td {
            border: 1px solid #111827;
            padding: 6px 5px;
            font-size: 8.5px;
            white-space: normal;
            color: #111827;
            text-align: left !important;
        }

        .type-pill {
            background: transparent !important;
            color: #111827 !important;
            padding: 0;
            border-radius: 0;
            font-size: 8.5px;
        }

        .fee-text {
            color: #111827 !important;
        }

        .total-row td {
            background: #f3f4f6 !important;
            border: 1px solid #111827;
            font-weight: 900;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 70px;
            margin-top: 35px;
            page-break-inside: avoid;
        }

        .signature-box {
            padding-top: 28px;
        }

        .signature-line {
            font-size: 11px;
        }

        .signature-label {
            font-size: 10px;
        }
    }
</style>

@php
    $computedTotalKg = $groupedSales->sum('total_palay_weight');
@endphp

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Daily Sales Report</h1>
        <p class="page-subtitle">Grouped daily sales summary for staff remittance and owner verification.</p>
    </div>

    <button type="button" class="btn-print" onclick="window.print()">Print Report</button>
</div>

<div class="report-card no-print">
    <form method="GET" action="{{ route('owner.reports') }}" class="filter-card">
        <div class="filter-grid">
            <div>
                <label class="field-label">Report Date</label>
                <input type="date" name="date" class="custom-input" value="{{ $date }}">
            </div>

            <div>
                <label class="field-label">Prepared By / Staff</label>
                <select name="staff_id" class="custom-input">
                    <option value="all" {{ $staffId === 'all' ? 'selected' : '' }}>All Staff</option>
                    @foreach($staffUsers as $staff)
                        <option value="{{ $staff->id }}" {{ (string)$staffId === (string)$staff->id ? 'selected' : '' }}>
                            {{ $staff->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="btn-main">
                    View Report
                </button>
            </div>
        </div>
    </form>
</div>

<div id="printArea">
    <div class="report-card">
        <div class="print-header">
            <img src="{{ asset('images/jk-logo.png') }}" class="print-logo" alt="JK Logo">
            <h2 class="print-business">JK Diez Rice Mill</h2>
            <p class="print-title">Daily Sales Report</p>
        </div>

        <div class="report-meta">
            <div class="meta-item">
                <strong>Date Covered:</strong> {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
            </div>

            <div class="meta-item">
                <strong>Generated On:</strong> {{ now()->format('F d, Y h:i A') }}
            </div>

            <div class="meta-item">
                <strong>Prepared By:</strong>
                @if($staffId === 'all')
                    All Staff
                @else
                    {{ $staffUsers->firstWhere('id', (int)$staffId)->name ?? 'Staff' }}
                @endif
            </div>

            <div class="meta-item">
                <strong>Checked By:</strong> Owner
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Total Sales</div>
                <div class="summary-value summary-green">
                    ₱{{ number_format($totalIncome, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Menudo Sales</div>
                <div class="summary-value summary-green">
                    ₱{{ number_format($menudoIncome, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Commercial Sales</div>
                <div class="summary-value summary-orange">
                    ₱{{ number_format($commercialIncome, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Total Palay Weight</div>
                <div class="summary-value">
                    {{ number_format($computedTotalKg, 2) }} kg
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Total Transactions</div>
                <div class="summary-value">
                    {{ $totalTransactions }}
                </div>
            </div>
        </div>

        <div class="section-head">
            <div>
                <h2 class="section-title">Daily Sales Breakdown</h2>
                <p class="section-subtitle">
                    Same client ID, milling type, and cashier are grouped into one row. Total Amount is the final amount paid after adjustments.
                </p>
            </div>

            <span class="record-badge">
                Records: {{ $groupedSales->count() }}
            </span>
        </div>

        <div class="table-wrap">
            <table class="sales-table">
                <colgroup>
                    <col style="width: 15%">
                    <col style="width: 12%">
                    <col style="width: 11%">
                    <col style="width: 13%">
                    <col style="width: 10%">
                    <col style="width: 14%">
                    <col style="width: 13%">
                    <col style="width: 12%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Milling Type</th>
                        <th>Transactions</th>
                        <th>Palay Weight</th>
                        <th>Fee / kg</th>
                        <th>Adjustment</th>
                        <th>Total Amount</th>
                        <th>Cashier</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($groupedSales as $sale)
                        @php
                            $feeUsed = $sale->total_palay_weight > 0
                                ? $sale->subtotal / $sale->total_palay_weight
                                : 0;
                        @endphp

                        <tr>
                            <td>
                                <span class="client-name">
                                    {{ $sale->client_name ?? 'Unknown Client' }}
                                </span>
                            </td>

                            <td>
                                <span class="type-pill {{ $sale->milling_type === 'menudo' ? 'type-menudo' : 'type-commercial' }}">
                                    {{ ucfirst($sale->milling_type) }}
                                </span>
                            </td>

                            <td class="text-right">
                                {{ $sale->transaction_count }}
                            </td>

                            <td class="text-right">
                                {{ number_format($sale->total_palay_weight, 2) }} kg
                            </td>

                            <td class="text-right fee-text">
                                ₱{{ number_format($feeUsed, 2) }}
                            </td>

                            <td>
                                @if((float) $sale->other_charges > 0)
                                    <span class="adjustment-line adjustment-charge">
                                        <span class="adjustment-amount">+₱{{ number_format($sale->other_charges, 2) }}</span>
                                        <span class="adjustment-kind">Charge</span>
                                    </span>
                                @endif
                                @if((float) $sale->discount > 0)
                                    <span class="adjustment-line adjustment-discount">
                                        <span class="adjustment-amount">−₱{{ number_format($sale->discount, 2) }}</span>
                                        <span class="adjustment-kind">Discount</span>
                                    </span>
                                @endif
                                @if((float) $sale->other_charges == 0 && (float) $sale->discount == 0)
                                    <span class="adjustment-line">None</span>
                                @endif
                            </td>

                            <td class="text-right amount-text">
                                ₱{{ number_format($sale->total_amount, 2) }}
                            </td>

                            <td>
                                {{ $sale->staff_name ?? 'Staff' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    No sales records found for the selected date.
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    @if($groupedSales->count() > 0)
                        <tr class="total-row">
                            <td colspan="2">Grand Total</td>
                            <td class="text-right">{{ $totalTransactions }}</td>
                            <td class="text-right">{{ number_format($computedTotalKg, 2) }} kg</td>
                            <td class="text-right">—</td>
                            <td>
                                <span class="adjustment-amount">{{ $otherCharges - $discounts > 0 ? '+' : ($otherCharges - $discounts < 0 ? '−' : '') }}₱{{ number_format(abs($otherCharges - $discounts), 2) }}</span>
                                <span class="adjustment-kind">Net Adjustment</span>
                            </td>
                            <td class="text-right">₱{{ number_format($totalIncome, 2) }}</td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="collection-summary">
            <h3 class="collection-summary-title">Collections &amp; Adjustments</h3>
            <div class="collection-summary-grid">
                <div class="collection-summary-item">
                    <span>Cash Collections</span>
                    <strong>₱{{ number_format($cashIncome, 2) }}</strong>
                </div>
                <div class="collection-summary-item">
                    <span>Digital Collections</span>
                    <strong>₱{{ number_format($digitalIncome, 2) }}</strong>
                </div>
                <div class="collection-summary-item">
                    <span>Other Charges</span>
                    <strong>₱{{ number_format($otherCharges, 2) }}</strong>
                </div>
                <div class="collection-summary-item">
                    <span>Discounts</span>
                    <strong>₱{{ number_format($discounts, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="signature-grid">
            <div class="signature-box">
                <div class="signature-line">Prepared By</div>
                <div class="signature-label">Staff Signature</div>
            </div>

            <div class="signature-box">
                <div class="signature-line">Checked By</div>
                <div class="signature-label">Owner Signature</div>
            </div>
        </div>
    </div>
</div>

@endsection
