@extends('layouts.staff')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
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
        padding: 22px 22px 18px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        height: 100%;
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

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
    }

    .summary-label {
        font-size: 1rem;
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
        font-size: 2.35rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        margin: 0 0 10px;
    }

    .summary-note {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .section-card {
        background: #fff;
        border: none;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
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

    .weight-cell {
        text-align: right;
        font-weight: 700;
    }

    .logs-wrapper {
        max-height: 280px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 12px;
    }

    .log-date {
        font-size: 0.92rem;
        white-space: nowrap;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 54px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .type-in {
        background: #dcfce7;
        color: #15803d;
    }

    .type-out {
        background: #fee2e2;
        color: #b91c1c;
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

        .logs-wrapper {
            max-height: 220px;
        }
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Inventory Overview</h1>
        <p class="page-subtitle">Track palay input and milled rice output from milling operations.</p>
    </div>
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
            <p class="summary-note">Raw palay stock currently logged in inventory.</p>
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
            <p class="summary-note">Completed milled rice stock recorded from actual output.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="section-card h-100">
            <h2 class="section-title">
                <i data-lucide="sprout"></i>
                Palay Inventory by Rice Type
            </h2>
            <p class="section-subtitle">Unmilled palay available in storage.</p>

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
                                <td colspan="2" class="text-center text-muted">No palay inventory yet.</td>
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
        <div class="section-card h-100">
            <h2 class="section-title">
                <i data-lucide="package"></i>
                Milled Rice Inventory by Rice Type
            </h2>
            <p class="section-subtitle">Finished rice ready for release.</p>

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
                                <td colspan="2" class="text-center text-muted">No milled rice inventory yet.</td>
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

<div class="section-card mt-4">
    <h2 class="section-title">
        <i data-lucide="clipboard-list"></i>
        Recent Inventory Logs
    </h2>
    <p class="section-subtitle">Latest stock movements.</p>

    <div class="logs-wrapper">
        <table class="table inventory-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Delivery ID</th>
                    <th>Rice Type</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th class="text-end">Qty (kg)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventoryLogs as $log)
                    <tr>
                        <td class="log-date">
                            {{ \Carbon\Carbon::parse($log->logged_at)->format('M d, Y h:i A') }}
                        </td>
                        <td>{{ $log->delivery->delivery_id ?? 'N/A' }}</td>
                        <td>{{ $log->delivery->riceType->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $log->stock_category)) }}</td>
                        <td>
                            <span class="type-badge {{ $log->type === 'in' ? 'type-in' : 'type-out' }}">
                                {{ strtoupper($log->type) }}
                            </span>
                        </td>
                        <td class="weight-cell">{{ number_format($log->quantity, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No inventory logs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection