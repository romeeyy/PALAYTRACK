@extends('layouts.staff')

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
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Deliveries</h1>
        <p class="page-subtitle">Manage palay delivery transactions and monitor the milling queue.</p>
    </div>

    <a href="/staff/record-delivery" class="btn btn-success quick-btn">
        Record New Delivery
    </a>
</div>

<div class="filter-card">
    <h2 class="card-title">Filter Deliveries</h2>
    <p class="card-subtitle">Narrow down records by delivery status.</p>

    <form method="GET" action="{{ url('/staff/deliveries') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <select name="status" class="form-select custom-select">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="claimed" {{ request('status') == 'claimed' ? 'selected' : '' }}>Claimed</option>
            </select>
        </div>

        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary filter-btn">
                Apply
            </button>

            <a href="/staff/deliveries" class="btn btn-outline-secondary filter-btn">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="table-card">
    <h2 class="card-title">Delivery List / Queue</h2>
    <p class="card-subtitle">First-Come, First-Served (FCFS) delivery monitoring.</p>

    <div class="table-responsive">
        <table class="table soft-table align-middle">
            <thead>
                <tr>
                    <th>Queue</th>
                    <th>Client</th>
                    <th>Rice</th>
                    <th>Details</th>
                    <th>Staff</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($deliveries as $delivery)
                <tr>
                    <td>
                        <span class="queue-badge">#{{ $delivery->queue_number }}</span>
                    </td>

                    <td>
                        <div class="client-name">{{ $delivery->client_name }}</div>
                        <div class="sub-text">{{ $delivery->delivery_id }}</div>
                    </td>

                    <td>{{ $delivery->riceType->name ?? 'N/A' }}</td>

                    <td>
                        <div><strong>{{ number_format($delivery->palay_weight, 2) }} kg</strong></div>
                        <div class="sub-text">{{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }} sacks • Est: {{ number_format($delivery->estimated_rice, 0) }}</div>
                        <div class="light-text">
                            {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y') : '' }}
                        </div>
                    </td>

                    <td>{{ $delivery->staff?->name ?? 'N/A' }}</td>

                    <td>
                        <span class="status-badge status-{{ $delivery->status }}">
                            {{ ucfirst($delivery->status) }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ url('/staff/delivery-details/' . $delivery->id) }}"
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
</div>

@endsection
