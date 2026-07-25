@extends('layouts.staff')

@section('content')

<style>
    .table-scroll-wrapper {
        border-radius: 16px;
    }

    .table-scroll-wrapper thead th {
        background: #ffffff;
    }

    .records-page {
        max-width: 100%;
    }

    .transactions-header {
        margin-bottom: 18px;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 900;
        margin: 0 0 8px;
        color: #0f172a;
        letter-spacing: -0.4px;
    }

    .hero-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        max-width: 700px;
    }

    .filter-panel {
        margin: 0 0 22px;
        background: #f8faf9;
        border: 1px solid #e3e9e2;
        border-radius: 16px;
        padding: 16px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: minmax(220px, 1.4fr) repeat(3, minmax(150px, 1fr)) auto;
        gap: 12px;
        align-items: end;
    }

    .filter-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.92rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
    }

    .filter-label i {
        width: 16px;
        height: 16px;
        color: #64748b;
    }

    .filter-date,
    .filter-select,
    .filter-search {
        width: 100%;
        border-radius: 12px;
        min-height: 48px;
        border: 1px solid #dbe3ec;
        background: #ffffff;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.03);
        font-weight: 600;
        color: #334155;
    }

    .filter-search::placeholder {
        color: #94a3b8;
        font-weight: 500;
    }

    .filter-date:focus,
    .filter-select:focus,
    .filter-search:focus {
        border-color: #2f5d1e;
        box-shadow: 0 0 0 3px rgba(47, 93, 30, 0.12);
    }

    .table-shell {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .table-shell-top {
        padding: 20px 22px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
    }

    .table-shell-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 900;
        color: #111827;
    }

    .table-shell-subtitle {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .record-pill {
        padding: 10px 14px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 900;
    }

    .table-wrap {
        padding: 8px 18px 20px;
    }

    .custom-table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .custom-table thead th {
        border-bottom: 1px solid #e5e7eb;
        color: #0f172a;
        font-size: 0.9rem;
        font-weight: 900;
        padding-top: 16px;
        padding-bottom: 16px;
        background: #ffffff;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
        border-color: #eef2f7;
        color: #1f2937;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .custom-table tbody tr:hover {
        background: #fafcfb;
    }

    .receipt-code {
        font-weight: 900;
        color: #0f172a;
    }

    .client-name {
        font-weight: 800;
        color: #111827;
    }

    .amount-text {
        font-weight: 900;
        color: #0f172a;
    }

    .badge-method {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 900;
        letter-spacing: 0.2px;
        border: 1px solid transparent;
    }

    .badge-cash {
        background: #ecfdf5;
        color: #15803d;
        border-color: #d1fae5;
    }

    .badge-gcash {
        background: #eff6ff;
        color: #2563eb;
        border-color: #dbeafe;
    }

    .badge-maya {
        background: #f5f3ff;
        color: #7c3aed;
        border-color: #ede9fe;
    }

    .btn-view {
        border-radius: 12px;
        font-weight: 800;
        min-width: 78px;
        padding-inline: 14px;
    }

    .filter-actions { display: flex; gap: 8px; }
    .filter-btn {
        min-height: 44px;
        border-radius: 11px;
        padding: 0 14px;
        font-weight: 800;
        font-size: 0.92rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        white-space: nowrap;
    }
    .filter-btn i { width: 15px; height: 15px; }
    .reset-btn { background: #ffffff; color: #475569; border-color: #cbd5e1; }
    .reset-btn:hover { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }
    .pagination-wrap { padding: 14px 20px; border-top: 1px solid #eef2f7; }

    .empty-state {
        text-align: center;
        padding: 42px 18px 46px;
        color: #64748b;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        border-radius: 18px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        border: 1px solid #e5e7eb;
    }

    .empty-icon i {
        width: 26px;
        height: 26px;
    }

    .empty-state-title {
        font-size: 1rem;
        font-weight: 900;
        color: #334155;
        margin-bottom: 6px;
    }

    .empty-state-text {
        margin: 0;
        font-size: 0.93rem;
    }

    @media (max-width: 992px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 768px) {
        .table-shell {
            border-radius: 18px;
        }

        .hero-title {
            font-size: 1.6rem;
        }

        .table-wrap {
            padding: 16px;
        }
    }
</style>

<div class="records-page">
    <div class="transactions-header">
        <div class="hero-top">
            <div>
                <h1 class="hero-title">My Transactions</h1>
                <p class="hero-subtitle">
                    Review payments and receipts processed by your account.
                </p>
            </div>

        </div>
    </div>

    <form method="GET" class="filter-panel">
        <div class="filter-grid">
                <div>
                    <label for="search" class="filter-label">
                        <i data-lucide="search"></i>
                        Search
                    </label>
                    <input id="search" type="search" name="search" value="{{ $search }}"
                           class="form-control filter-search" placeholder="Client, delivery, or receipt">
                </div>

                <div>
                    <label for="date" class="filter-label">
                        <i data-lucide="calendar-days"></i>
                        Filter by Date
                    </label>

                    <input
                        id="date"
                        type="date"
                        name="date"
                        value="{{ $date }}"
                        class="form-control filter-date"
                    >
                </div>

                <div>
                    <label for="milling_type" class="filter-label">
                        <i data-lucide="sliders-horizontal"></i>
                        Milling Type
                    </label>

                    <select
                        id="milling_type"
                        name="milling_type"
                        class="form-select filter-select"
                    >
                        <option value="all" {{ ($millingType ?? 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="menudo" {{ ($millingType ?? '') == 'menudo' ? 'selected' : '' }}>Menudo</option>
                        <option value="commercial" {{ ($millingType ?? '') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="filter-label">
                        <i data-lucide="wallet-cards"></i>
                        Payment Method
                    </label>
                    <select id="payment_method" name="payment_method" class="form-select filter-select">
                        <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Methods</option>
                        <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gcash" {{ $paymentMethod === 'gcash' ? 'selected' : '' }}>GCash</option>
                        <option value="maya" {{ $paymentMethod === 'maya' ? 'selected' : '' }}>Maya</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-success filter-btn">
                        <i data-lucide="search"></i>
                        Filter
                    </button>
                    <a href="{{ route('staff.transactions', ['date' => now()->toDateString()]) }}"
                       class="btn btn-outline-secondary filter-btn reset-btn"
                       aria-label="Reset all transaction filters">
                        <i data-lucide="rotate-ccw"></i>
                        Reset
                    </a>
                </div>
        </div>
    </form>

    <div class="table-shell">
        <div class="table-shell-top">
            <div>
                <h2 class="table-shell-title">Recorded Transactions</h2>
                <p class="table-shell-subtitle">
                    Individual transaction entries used for checking receipts and tracing transaction details.
                </p>
            </div>

            <div class="record-pill">Records: {{ $transactions->total() }}</div>
        </div>

        @if($transactions->count())
            <div class="table-wrap">
                <div class="table-responsive table-scroll-wrapper">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Receipt</th>
                                <th>Client</th>
                                <th>Milling Type</th>
                                <th>Palay Weight (kg)</th>
                                <th>Fee / kg</th>
                                <th>Total</th>
                                <th>Method</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($transactions as $t)
                                <tr>
                                    <td class="receipt-code">
                                        TXN-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td class="client-name">
                                        {{ $t->delivery->client_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ ucfirst($t->milling_type ?? 'N/A') }}
                                    </td>

                                    <td>
                                        {{ number_format($t->palay_weight_kg, 2) }} kg
                                    </td>

                                    <td class="amount-text">
                                        ₱{{ number_format($t->milling_fee_per_kg, 2) }}/kg
                                    </td>

                                    <td class="amount-text">
                                        ₱{{ number_format($t->total_amount, 2) }}
                                    </td>

                                    <td>
                                        @php
                                            $methodClass = match($t->payment_method) {
                                                'cash' => 'badge-cash',
                                                'gcash' => 'badge-gcash',
                                                'maya' => 'badge-maya',
                                                default => 'badge-cash',
                                            };
                                        @endphp

                                        <span class="badge-method {{ $methodClass }}">
                                            {{ strtoupper($t->payment_method) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ ($t->paid_at ?? $t->created_at)->format('m/d/Y h:i A') }}
                                    </td>

                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('staff.receipt', $t->delivery_id) }}"
                                               class="btn btn-sm btn-outline-success btn-view">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="pagination-wrap">{{ $transactions->links() }}</div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i data-lucide="inbox"></i>
                </div>
                <div class="empty-state-title">No transaction records found</div>
                <p class="empty-state-text">There are no recorded transactions for the selected filters.</p>
            </div>
        @endif
    </div>
</div>

<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>

@endsection
