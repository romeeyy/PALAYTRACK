@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        margin-bottom: 22px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
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

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #d1d5db;
        color: #334155;
        padding: 10px 14px;
        border-radius: 11px;
        font-weight: 800;
        font-size: 0.88rem;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-back:hover {
        background: #f8fafc;
        border-color: #2f5d1e;
        color: #2f5d1e;
        text-decoration: none;
    }

    .settings-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 18px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 15px;
        background: #f8fafc;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-label {
        font-weight: 900;
        color: #111827;
        font-size: 0.85rem;
    }

    .filter-select {
        min-height: 42px;
        min-width: 220px;
        border-radius: 11px;
        border: 1px solid #d1d5db;
        padding: 9px 12px;
        font-weight: 700;
        color: #334155;
        background: #ffffff;
    }

    .filter-select:focus {
        border-color: #2f5d1e;
        box-shadow: 0 0 0 3px rgba(47, 93, 30, 0.10);
        outline: none;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-filter {
        background: #2f5d1e;
        color: #ffffff;
        border: none;
        padding: 10px 15px;
        border-radius: 11px;
        font-weight: 800;
        font-size: 0.88rem;
        text-decoration: none;
    }

    .btn-filter:hover {
        background: #274d19;
        color: #ffffff;
    }

    .btn-reset {
        background: #ffffff;
        color: #334155;
        border: 1px solid #d1d5db;
        padding: 10px 15px;
        border-radius: 11px;
        font-weight: 800;
        font-size: 0.88rem;
        text-decoration: none;
    }

    .btn-reset:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .table-wrap {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .table-scroll {
        max-height: 420px;
        overflow-y: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .history-table thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        font-size: 0.78rem;
        font-weight: 900;
        text-transform: uppercase;
        color: #475569;
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .history-table tbody td {
        padding: 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
        white-space: nowrap;
    }

    .history-table tbody tr:hover {
        background: #f9fafb;
    }

    .pill-type {
        background: #eff6ff;
        color: #1d4ed8;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 900;
    }

    .fee-old {
        background: #fef2f2;
        color: #b91c1c;
        padding: 6px 10px;
        border-radius: 10px;
        font-weight: 800;
    }

    .fee-new {
        background: #ecfdf5;
        color: #047857;
        padding: 6px 10px;
        border-radius: 10px;
        font-weight: 800;
    }

    .changed-by {
        font-weight: 800;
        color: #0f172a;
    }

    .date-text {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 35px;
        color: #64748b;
        font-weight: 700;
    }

    .table-footer {
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .footer-text {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 700;
        margin: 0;
    }

    @media (max-width: 768px) {
        .table-scroll {
            max-height: 300px;
        }

        .btn-back,
        .btn-filter,
        .btn-reset,
        .filter-select {
            width: 100%;
        }

        .filter-group,
        .filter-actions {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Milling Fee History</h1>
        <p class="page-subtitle">Complete audit trail of all milling fee changes.</p>
    </div>

    <a href="{{ route('owner.settings') }}" class="btn-back">
        ← Back to Settings
    </a>
</div>

<div class="settings-card">

    <form method="GET" action="{{ route('owner.milling-fee-history') }}" class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Filter by Milling Type</label>
            <select name="milling_type" class="filter-select">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                <option value="menudo" {{ $type === 'menudo' ? 'selected' : '' }}>Menudo</option>
                <option value="commercial" {{ $type === 'commercial' ? 'selected' : '' }}>Commercial</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter">
                Apply Filter
            </button>

            <a href="{{ route('owner.milling-fee-history') }}" class="btn-reset">
                Reset
            </a>
        </div>
    </form>

    <div class="table-wrap">
        <div class="table-scroll">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Milling Type</th>
                        <th>Old Fee</th>
                        <th>New Fee</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td>
                                <span class="pill-type">
                                    {{ ucfirst($history->milling_type) }}
                                </span>
                            </td>

                            <td>
                                <span class="fee-old">
                                    ₱{{ number_format($history->old_fee, 2) }}/kg
                                </span>
                            </td>

                            <td>
                                <span class="fee-new">
                                    ₱{{ number_format($history->new_fee, 2) }}/kg
                                </span>
                            </td>

                            <td>
                                <span class="date-text">
                                    {{ \Carbon\Carbon::parse($history->changed_at)->format('M d, Y h:i A') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    No milling fee history found for this filter.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-footer">
        <p class="footer-text">
            Showing paginated records for audit tracking.
        </p>

        <div>
            {{ $histories->links() }}
        </div>
    </div>
</div>

@endsection
