@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        margin-bottom: 16px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 4px;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .summary-card {
        border: none;
        border-radius: 20px;
        padding: 18px 18px 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-top: 5px solid #15803d;
    }

    .summary-card.orange {
        background: linear-gradient(135deg, #ffffff 0%, #fffaf0 100%);
        border-top-color: #f59e0b;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        right: -35px;
        bottom: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(47, 93, 30, 0.08);
    }

    .summary-card.orange::after {
        background: rgba(245, 158, 11, 0.12);
    }

    .summary-top,
    .summary-value,
    .summary-note {
        position: relative;
        z-index: 1;
    }

    .main-content .summary-card .summary-value {
        font-size: 1.75rem;
        font-weight: 900;
    }

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .summary-label {
        font-size: 0.98rem;
        color: #334155;
        font-weight: 800;
        line-height: 1.5;
        margin: 0;
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eef6ea;
        color: #2f5d1e;
    }

    .summary-card.orange .summary-icon {
        background: #fff7ed;
        color: #ea580c;
    }

    .summary-icon svg {
        width: 24px;
        height: 24px;
        stroke-width: 2.2;
    }

    .summary-value {
        font-size: 2.2rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
        margin: 0 0 6px;
    }

    .summary-note {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
        line-height: 1.5;
    }

    .section-card {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        height: auto;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.2rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .section-title i {
        color: #16a34a;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .inventory-table {
        margin-bottom: 0;
    }

    .inventory-table thead th {
        background: #f8fafc;
        color: #334155;
        font-size: 0.92rem;
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .inventory-table tbody td {
        color: #111827;
        font-size: 0.95rem;
        padding: 14px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
    }

    .inventory-table tbody tr:hover {
        background: #f8fafc;
    }

    .inventory-table tbody tr:last-child td {
        border-bottom: none;
    }

    .inventory-table .total-row td {
        font-weight: 800;
    }

    .palay-total td {
        background: #eef8f1;
    }

    .milled-total td {
        background: #fdf8ea;
    }

    .grand-total td {
        background: #f8fafc;
        font-weight: 800;
    }

    .weight-cell {
        text-align: right;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        color: #64748b;
        padding: 28px 12px;
        font-size: 0.95rem;
    }

    .empty-state-content { display:flex; flex-direction:column; align-items:center; gap:7px; }
    .empty-state-content svg { width:22px; height:22px; color:#94a3b8; }
    .empty-state-content span { color:#64748b; }

    .full-width-card {
        margin-top: 24px;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .summary-value {
            font-size: 2rem;
        }

        .section-card {
            padding: 18px;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Inventory Management</h1>
    <p class="page-subtitle">Track palay and milled rice inventory.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card summary-card">
            <div class="summary-top">
                <p class="summary-label">Total Palay Inventory</p>
                <div class="summary-icon">
                    <i data-lucide="sprout"></i>
                </div>
            </div>

            <h2 class="summary-value">{{ number_format($totalPalay, 2) }} kg</h2>
            <p class="summary-note">Total palay waiting for milling or still being milled.</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card summary-card orange">
            <div class="summary-top">
                <p class="summary-label">Total Milled Rice Inventory</p>
                <div class="summary-icon">
                    <i data-lucide="package"></i>
                </div>
            </div>

            <h2 class="summary-value">{{ number_format($totalMilledRice, 2) }} kg</h2>
            <p class="summary-note">Total milled rice not yet claimed.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="section-card">
            <h2 class="section-title">
                <i data-lucide="sprout"></i>
                Palay Inventory by Rice Type
            </h2>
            <p class="section-subtitle">Palay stock grouped by rice type.</p>

            <div class="table-responsive">
                <table class="table inventory-table align-middle">
                    <thead>
                        <tr>
                            <th>Rice Type</th>
                            <th class="text-end">Weight (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($palayByRiceType as $item)
                        <tr>
                            <td>{{ $item->rice_type_name }}</td>
                            <td class="weight-cell">{{ number_format($item->total_weight, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="empty-state"><div class="empty-state-content"><i data-lucide="sprout"></i><span>No palay inventory yet.</span></div></td>
                        </tr>
                        @endforelse

                        <tr class="total-row palay-total">
                            <td>Total</td>
                            <td class="weight-cell">{{ number_format($totalPalay, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="section-card">
            <h2 class="section-title">
                <i data-lucide="package"></i>
                Milled Rice Inventory by Rice Type
            </h2>
            <p class="section-subtitle">Unclaimed milled rice grouped by rice type.</p>

            <div class="table-responsive">
                <table class="table inventory-table align-middle">
                    <thead>
                        <tr>
                            <th>Rice Type</th>
                            <th class="text-end">Weight (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($milledByRiceType as $item)
                        <tr>
                            <td>{{ $item->rice_type_name }}</td>
                            <td class="weight-cell">{{ number_format($item->total_weight, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="empty-state"><div class="empty-state-content"><i data-lucide="package-open"></i><span>No milled rice inventory yet.</span></div></td>
                        </tr>
                        @endforelse

                        <tr class="total-row milled-total">
                            <td>Total</td>
                            <td class="weight-cell">{{ number_format($totalMilledRice, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="section-card full-width-card">
    <h2 class="section-title">
        <i data-lucide="clipboard-list"></i>
        Complete Inventory Overview
    </h2>
    <p class="section-subtitle">Combined palay and milled rice inventory across all rice types.</p>

    <div class="table-responsive">
        <table class="table inventory-table align-middle">
            <thead>
                <tr>
                    <th>Rice Type</th>
                    <th class="text-end">Palay Weight (kg)</th>
                    <th class="text-end">Milled Rice Weight (kg)</th>
                    <th class="text-end">Total (kg)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($combinedInventory as $item)
                <tr>
                    <td>{{ $item['rice_type_name'] }}</td>
                    <td class="weight-cell">{{ number_format($item['palay_weight'], 2) }}</td>
                    <td class="weight-cell">{{ number_format($item['milled_weight'], 2) }}</td>
                    <td class="weight-cell">{{ number_format($item['total_weight'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state"><div class="empty-state-content"><i data-lucide="clipboard-list"></i><span>No inventory data yet.</span></div></td>
                </tr>
                @endforelse

                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="weight-cell">{{ number_format($totalPalay, 2) }}</td>
                    <td class="weight-cell">{{ number_format($totalMilledRice, 2) }}</td>
                    <td class="weight-cell">{{ number_format($totalPalay + $totalMilledRice, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
