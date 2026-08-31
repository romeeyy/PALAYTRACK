@extends('layouts.owner')

@section('content')
<style>
    body {
        overflow-x: hidden;
    }

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
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .quick-btn {
        min-height: 48px;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
    }

    .filter-card,
    .table-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8e4;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.05);
    }

    .filter-card {
        padding: 16px 18px;
        margin-bottom: 18px;
    }

    .queue-tabs {
        display: inline-flex;
        gap: 4px;
        padding: 4px;
        margin: 0 0 14px;
        background: #ffffff;
        border: 1px solid #dfe7df;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .04);
    }

    .queue-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 7px 14px;
        border-radius: 8px;
        color: #475569;
        font-weight: 700;
        text-decoration: none;
    }

    .queue-tab.active {
        background: #166534;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(22, 101, 52, .18);
    }

    .queue-tab:not(.active):hover {
        background: #f4f8f3;
        color: #166534;
    }

    .queue-count {
        min-width: 24px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #eef2f7;
        text-align: center;
        font-size: 0.76rem;
    }

    .queue-tab.active .queue-count { background: rgba(255,255,255,.18); color: #ffffff; }

    .filter-grid {
        display: grid;
        grid-template-columns: minmax(240px, 1.5fr) minmax(180px, 1fr) minmax(180px, 1fr) auto;
        gap: 14px;
        align-items: end;
    }

    .filter-label {
        display: block;
        color: #334155;
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .filter-control {
        min-height: 48px;
        border-radius: 12px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .card-title {
        font-size: 1.22rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .card-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 14px;
    }

    .custom-select {
        border-radius: 14px;
        min-height: 52px;
    }

    .filter-btn {
        height: 44px;
        padding: 0 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .queue-badge {
        padding: 6px 12px;
        border-radius: 999px;
        background: #ecfdf3;
        color: #15803d;
        font-weight: 800;
        font-size: 0.85rem;
    }

    .soft-table thead th {
        background: #f8fafc;
        font-weight: 700;
        white-space: nowrap;
        padding: 12px;
        font-size: 0.9rem;
    }

    .soft-table tbody td {
        padding: 14px 12px;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .soft-table tbody tr:hover {
        background: #f8fafc;
    }

    .soft-table {
        min-width: 1050px;
    }

    .client-name {
        font-weight: 700;
    }

    .sub-text {
        font-size: 0.85rem;
        color: #64748b;
    }

    .light-text {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        min-width: 118px;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #a16207;
    }

    .status-processing {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-completed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-claimed {
        background: #eef2f7;
        color: #475569;
    }

    .status-payment-required {
        background: #fff7ed;
        color: #c2410c;
    }

    .status-awaiting-notification {
        background: #fefce8;
        color: #a16207;
    }

    .status-ready-for-claim {
        background: #dcfce7;
        color: #15803d;
    }

    .details-btn {
        border-radius: 10px;
        padding: 7px 13px;
        font-weight: 600;
        min-width: 118px;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #64748b;
    }

    .pagination-wrap { padding-top: 18px; }

    /* Shared compact module styling */
    .page-header { margin-bottom: 16px; }
    .page-title { font-size: 1.8rem; }
    .page-subtitle { font-size: .92rem; }
    .quick-btn { min-height: 44px; border-radius: 10px; padding: 9px 14px; }
    .filter-card,
    .table-card {
        border: 1px solid #e3e9e1;
        border-radius: 20px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, .06);
    }
    .filter-card { padding: 16px 18px; margin-bottom: 18px; }
    .table-card { padding: 20px 22px; }
    .queue-tabs { margin-bottom: 14px; }
    .queue-tab { min-height: 38px; }
    .filter-grid { gap: 12px; }
    .filter-label { margin-bottom: 6px; }
    .filter-control { min-height: 44px; border-radius: 11px; }
    .soft-table tbody td { padding-top: 12px; padding-bottom: 12px; }
    .soft-table .status-column,
    .soft-table .actions-column {
        text-align: center;
        vertical-align: middle;
    }
    .soft-table .status-column { width: 165px; }
    .soft-table .actions-column { width: 165px; }
    .pagination-wrap { padding-top: 12px; }

    @media (max-width: 1100px) {
        .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 700px) {
        .filter-grid { grid-template-columns: 1fr; }
        .filter-actions .filter-btn { flex: 1; }
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Deliveries</h1>
        <p class="page-subtitle">Manage palay delivery transactions and monitor the milling queue.</p>
    </div>

    <a href="/owner/record-delivery" class="btn btn-success quick-btn">
        Record New Delivery
    </a>
</div>

<nav class="queue-tabs" aria-label="Delivery record view">
    <a href="{{ route('owner.deliveries', ['view' => 'active']) }}"
       class="queue-tab {{ $selectedView === 'active' ? 'active' : '' }}">
        Active Queue
    </a>
    <a href="{{ route('owner.deliveries', ['view' => 'history']) }}"
       class="queue-tab {{ $selectedView === 'history' ? 'active' : '' }}">
        Claimed History
    </a>
</nav>

<div class="filter-card">
    <form method="GET" action="{{ route('owner.deliveries') }}" class="filter-grid">
        <input type="hidden" name="view" value="{{ $selectedView }}">
        <div>
            <label for="delivery-search" class="filter-label">Search Delivery</label>
            <input id="delivery-search" type="search" name="search" value="{{ request('search') }}"
                   class="form-control filter-control" placeholder="Client name or delivery ID">
        </div>

        <div>
            <label for="delivery-date" class="filter-label">Delivery Date</label>
            <input id="delivery-date" type="date" name="date" value="{{ request('date') }}"
                   class="form-control filter-control">
        </div>

        <div>
            <label for="delivery-status" class="filter-label">Status</label>
            <select id="delivery-status" name="status" class="form-select filter-control">
                @if($selectedView === 'active')
                    <option value="">All Active Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                @else
                    <option value="claimed">Claimed</option>
                @endif
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-success filter-btn">
                Filter
            </button>

            <a href="{{ route('owner.deliveries', ['view' => $selectedView]) }}" class="btn btn-outline-secondary filter-btn">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="table-card">
    <h2 class="card-title">{{ $selectedView === 'active' ? 'Active Delivery Queue' : 'Claimed Delivery History' }}</h2>
    <p class="card-subtitle">
        {{ $selectedView === 'active'
            ? 'Pending and processing deliveries follow First-Come, First-Served (FCFS) order.'
            : 'Completed records retained for payment, receipt, inventory, and audit reference.' }}
    </p>

    <div class="table-responsive">
        <table class="table soft-table align-middle">
            <thead>
                <tr>
                    <th>Queue</th>
                    <th>Client</th>
                    <th>Rice</th>
                    <th>Details</th>
                    <th>Recorded By</th>
                    <th class="status-column">Status</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($deliveries as $delivery)
                    @php
                        $isPaid = $delivery->transaction?->payment_status === 'paid';
                        $isNotified = $delivery->hasSuccessfulNotification();
                        $displayStatus = match ($delivery->status) {
                            'completed' => !$isNotified ? 'Awaiting Notification' : ($isPaid ? 'Ready for Claim' : 'Payment Required'),
                            default => ucfirst($delivery->status),
                        };
                        $statusClass = match ($delivery->status) {
                            'completed' => !$isNotified ? 'awaiting-notification' : ($isPaid ? 'ready-for-claim' : 'payment-required'),
                            default => $delivery->status,
                        };
                    @endphp
                    <tr>
                        <td>
                            <span class="queue-badge">#{{ $delivery->queue_number }}</span>
                            <div class="light-text mt-1">Daily queue</div>
                        </td>

                        <td>
                            <div class="client-name">{{ $delivery->client_name }}</div>
                            <div class="sub-text">{{ $delivery->delivery_id }}</div>
                        </td>

                        <td>{{ $delivery->riceType->name ?? 'N/A' }}</td>

                        <td>
                            <div><strong>{{ number_format($delivery->palay_weight, 2) }} kg</strong></div>
                            <div class="sub-text">
                                {{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }} sacks | Est: {{ number_format($delivery->estimated_rice, 2) }} kg
                            </div>
                            <div class="light-text">
                                {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y') : '' }}
                            </div>
                        </td>

                        <td>
                            <div>{{ $delivery->staff?->name ?? 'Unknown' }}</div>
                            <div class="sub-text">
                                {{ $delivery->staff?->role ? ucfirst($delivery->staff->role) : 'Account unavailable' }}
                            </div>
                        </td>

                        <td class="status-column">
                            <span class="status-badge status-{{ $statusClass }}">
                                {{ $displayStatus }}
                            </span>
                        </td>

                        <td class="actions-column">
                            <a href="{{ url('/owner/delivery-details/' . $delivery->id) }}"
                               class="btn btn-outline-success btn-sm details-btn">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state text-center">
                            <div style="padding: 30px 0;">
                                <strong>No deliveries found</strong><br>
                                <span class="sub-text">Try changing the filter or record a new delivery.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($deliveries->hasPages())
        <div class="pagination-wrap">
            {{ $deliveries->links() }}
        </div>
    @endif
</div>

@endsection
