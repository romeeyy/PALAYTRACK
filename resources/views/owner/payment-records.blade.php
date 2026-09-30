@extends('layouts.owner')

@section('content')

<style>
    .table-scroll-wrapper {
        max-height: 420px;
        overflow-y: auto;
        border-radius: 16px;
    }

    .table-scroll-wrapper thead th {
        position: sticky;
        top: 0;
        background: #ffffff;
        z-index: 3;
    }

    .table-scroll-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    .table-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .records-page {
        max-width: 100%;
    }

    .hero-card {
        background: transparent;
        border-radius: 0;
        padding: 0;
        box-shadow: none;
        border: 0;
        margin-bottom: 16px;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
    }

    .hero-title {
        font-size: 1.75rem;
        font-weight: 900;
        margin: 0 0 4px;
        color: #0f172a;
        letter-spacing: -0.4px;
    }

    .hero-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        max-width: 700px;
    }

    .filter-panel {
        margin-top: 15px;
        background: linear-gradient(135deg, #fbfefb 0%, #eef7f0 100%);
        border: 1px solid #cbdccf;
        border-radius: 18px;
        padding: 12px 14px 11px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.045);
    }

    .filter-row {
        display: grid;
        gap: 10px;
        align-items: end;
    }

    .filter-row-top {
        grid-template-columns: minmax(0, 1.5fr) minmax(180px, 0.7fr);
        margin-bottom: 10px;
    }

    .filter-row-bottom {
        grid-template-columns: minmax(160px, 1fr) minmax(160px, 1fr) minmax(180px, 1fr) auto;
    }

    .filter-search-group {
        min-width: 0;
    }

    .filter-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.92rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 4px;
    }

    .filter-label i {
        width: 16px;
        height: 16px;
        color: #64748b;
    }

    .filter-date,
    .filter-select,
    .filter-search {
        max-width: none;
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

    .date-help {
        display: block;
        margin-top: 6px;
        color: #64748b;
        font-size: 0.78rem;
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        align-self: end;
        padding-top: 0;
    }

    .filter-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 44px;
        padding: 7px 14px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.96rem;
        white-space: nowrap;
    }

    .filter-btn i {
        width: 16px;
        height: 16px;
    }

    .reset-btn {
        background: #ffffff;
        color: #475569;
        border-color: #cbd5e1;
    }

    .table-shell {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 14px 30px rgba(24, 65, 35, 0.09);
        border: 1px solid #cbdccf;
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
        background: #ffffff;
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
        background: #edf8f0;
        border: 1px solid #ccebd5;
        color: #187340;
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
        border-bottom: 1px solid #dbe3ec;
        color: #244c2d;
        font-size: 0.9rem;
        font-weight: 800;
        letter-spacing: -0.01em;
        padding-top: 16px;
        padding-bottom: 16px;
        background: #edf5ef;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
        border-color: #eef2f7;
        color: #1f2937;
        font-size: 0.9rem;
        white-space: nowrap;
        min-width: 108px;
    }

    .custom-table tbody tr:hover {
        background: #fafcfb;
    }

    .custom-table tbody tr:nth-child(even) {
        background: #fcfdfc;
    }

    .table-wrap { border-radius: 14px; overflow-x: auto; }
    .custom-table thead th:first-child { border-top-left-radius: 14px; }
    .custom-table thead th:last-child { border-top-right-radius: 14px; }

    .custom-table .numeric-cell {
        font-variant-numeric: tabular-nums;
    }

    .receipt-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 0.84rem;
        font-weight: 800;
        color: #0f172a;
    }

    .client-name {
        font-weight: 700;
        color: #111827;
    }

    .amount-text {
        font-weight: 700;
        color: #0f172a;
    }

    .date-cell {
        color: #475569;
        font-variant-numeric: tabular-nums;
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

    .empty-state {
        text-align: center;
        padding: 42px 18px 46px;
        color: #64748b;
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
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .filter-search-group {
            grid-column: span 2;
        }

        .filter-date,
        .filter-select {
            max-width: 100%;
        }
    }

    @media (max-width: 640px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-search-group {
            grid-column: span 1;
        }

        .filter-actions {
            justify-content: stretch;
        }

        .filter-actions>* {
            flex: 1;
        }
    }

    @media (max-width: 768px) {

        .hero-card,
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
    <div class="hero-card">
        <div class="hero-top">
            <div>
                <h1 class="hero-title">Transaction Records</h1>
                <p class="hero-subtitle">
                    Review payments, receipts, and cashier activity.
                </p>
            </div>

        </div>

        <form method="GET" class="filter-panel">
            <div class="filter-row filter-row-top">
                <div class="filter-search-group">
                    <label for="search" class="filter-label">
                        Search Transactions
                    </label>

                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control filter-search"
                        placeholder="Client, delivery, or receipt">
                </div>

                <div>
                    <label for="date" class="filter-label">
                        Filter by Date
                    </label>

                    <input
                        id="date"
                        type="date"
                        name="date"
                        value="{{ $date }}"
                        class="form-control filter-date">
                </div>
            </div>

            <div class="filter-row filter-row-bottom">
                <div>
                    <label for="milling_type" class="filter-label">
                        Milling Type
                    </label>

                    <select
                        id="milling_type"
                        name="milling_type"
                        class="form-select filter-select">
                        <option value="all" {{ ($millingType ?? 'all') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="menudo" {{ ($millingType ?? '') == 'menudo' ? 'selected' : '' }}>Menudo</option>
                        <option value="commercial" {{ ($millingType ?? '') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="filter-label">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        class="form-select filter-select">
                        <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Methods</option>
                        <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gcash" {{ $paymentMethod === 'gcash' ? 'selected' : '' }}>GCash</option>
                        <option value="maya" {{ $paymentMethod === 'maya' ? 'selected' : '' }}>Maya</option>
                    </select>
                </div>

                <div>
                    <label for="staff_id" class="filter-label">
                        Cashier
                    </label>

                    <select
                        id="staff_id"
                        name="staff_id"
                        class="form-select filter-select">
                        <option value="all" {{ $staffId === 'all' ? 'selected' : '' }}>All Cashiers</option>
                        @foreach($staffUsers as $staff)
                        <option value="{{ $staff->id }}" {{ (string) $staffId === (string) $staff->id ? 'selected' : '' }}>
                            {{ $staff->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-success filter-btn">
                        <i data-lucide="sliders-horizontal"></i>
                        Filter
                    </button>
                    <a href="{{ route('owner.payment-records', ['date' => now()->toDateString()]) }}"
                        class="btn btn-outline-secondary filter-btn reset-btn"
                        aria-label="Reset all transaction filters">
                        <i data-lucide="rotate-ccw"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-shell">
        <div class="table-shell-top">
            <div>
                <h2 class="table-shell-title">Recorded Transactions</h2>
                <p class="table-shell-subtitle">
                    Individual transaction entries used for checking receipts and tracing transaction details.
                </p>
            </div>

            <div class="record-pill">Records: {{ $totalRecords }}</div>
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
                            <th>Milling Fee</th>
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

                            <td class="numeric-cell">
                                {{ number_format($t->palay_weight_kg, 2) }} kg
                            </td>

                            <td class="numeric-cell amount-text">
                                ₱{{ number_format($t->milling_fee_per_kg, 2) }}/kg
                            </td>

                            <td class="numeric-cell amount-text">
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

                            <td class="date-cell">
                                {{ ($t->paid_at ?? $t->created_at)->format('M d, Y g:i A') }}
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('owner.receipt', $t->delivery_id) }}"
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
        @else
        <div class="empty-state">
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
