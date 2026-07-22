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
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    }

    .filter-card {
        margin-bottom: 24px;
    }

    .queue-tabs {
        display: inline-flex;
        gap: 6px;
        padding: 5px;
        margin-bottom: 18px;
        background: #eef2f7;
        border-radius: 14px;
    }

    .queue-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 8px 14px;
        border-radius: 10px;
        color: #475569;
        font-weight: 700;
        text-decoration: none;
    }

    .queue-tab.active {
        background: #ffffff;
        color: #166534;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    .queue-count {
        min-width: 24px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #e2e8f0;
        text-align: center;
        font-size: 0.76rem;
    }

    .queue-tab.active .queue-count { background: #dcfce7; color: #15803d; }

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
        font-size: 1.15rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .card-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 20px;
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
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
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

    .details-btn {
        border-radius: 10px;
        padding: 6px 12px;
        font-weight: 600;
        min-width: 90px;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #64748b;
    }

    .pagination-wrap { padding-top: 18px; }

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
        Active Queue <span class="queue-count">{{ $activeCount }}</span>
    </a>
    <a href="{{ route('owner.deliveries', ['view' => 'history']) }}"
       class="queue-tab {{ $selectedView === 'history' ? 'active' : '' }}">
        Claimed History <span class="queue-count">{{ $claimedCount }}</span>
    </a>
</nav>

<div class="filter-card">
    <h2 class="card-title">Filter Deliveries</h2>
    <p class="card-subtitle">Search a delivery or narrow the list by date and status.</p>

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
            <button type="submit" class="btn btn-primary filter-btn">
                Apply
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
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($deliveries as $delivery)
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

                        <td>
                            <span class="status-badge status-{{ $delivery->status }}">
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </td>

                        <td>
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
