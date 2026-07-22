@extends('layouts.owner')

@section('content')
<style>
    .history-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; }
    .history-title { margin:0 0 4px; font-size:1.8rem; font-weight:900; color:#0f172a; }
    .history-subtitle { margin:0; color:#64748b; }
    .back-link { border:1px solid #bbd7c0; color:#166534; background:#fff; padding:9px 13px; border-radius:10px; text-decoration:none; font-weight:800; white-space:nowrap; }
    .history-card { background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; box-shadow:0 8px 22px rgba(15,23,42,.04); }
    .history-table-wrap { overflow-x:auto; }
    .history-table { width:100%; border-collapse:collapse; }
    .history-table th { background:#f8fafc; color:#475569; font-size:.78rem; font-weight:900; padding:12px 15px; text-align:left; white-space:nowrap; }
    .history-table td { padding:12px 15px; border-top:1px solid #eef2f7; color:#334155; font-size:.86rem; }
    .event-badge { display:inline-flex; padding:4px 8px; border-radius:999px; background:#ecfdf5; color:#166534; font-size:.72rem; font-weight:800; }
    .empty { text-align:center; padding:30px !important; color:#64748b; }
    .pagination-row { padding:12px 15px; border-top:1px solid #eef2f7; display:flex; justify-content:space-between; gap:10px; color:#64748b; font-size:.82rem; }
    .pagination-actions { display:flex; gap:7px; }
    .page-link-simple { border:1px solid #dbe3ec; border-radius:8px; padding:6px 10px; color:#334155; text-decoration:none; font-weight:700; }
    .page-link-simple.disabled { opacity:.45; pointer-events:none; }
</style>

<div class="history-head">
    <div>
        <h1 class="history-title">Staff Account History</h1>
        <p class="history-subtitle">Account creation, profile, password, and access-status changes.</p>
    </div>
    <a href="{{ route('owner.staff-accounts') }}" class="back-link">Back to Staff Accounts</a>
</div>

<div class="history-card">
    <div class="history-table-wrap">
        <table class="history-table">
            <thead><tr><th>Date & Time</th><th>Staff</th><th>Action</th><th>Field</th><th>Old Value</th><th>New Value</th><th>Owner</th></tr></thead>
            <tbody>
            @forelse($histories as $history)
                <tr>
                    <td>{{ $history->changed_at->format('M d, Y h:i A') }}</td>
                    <td>{{ $history->staff->name ?? 'Unknown staff' }}</td>
                    <td><span class="event-badge">{{ str_replace('_', ' ', ucfirst($history->action)) }}</span></td>
                    <td>{{ $history->field ? ucfirst($history->field) : '—' }}</td>
                    <td>{{ $history->old_value ?? '—' }}</td>
                    <td>{{ $history->new_value ?? '—' }}</td>
                    <td>{{ $history->owner->name ?? 'Owner' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">No staff account changes recorded yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
        <div class="pagination-row">
            <span>Showing {{ $histories->firstItem() }}–{{ $histories->lastItem() }} of {{ $histories->total() }}</span>
            <div class="pagination-actions">
                <a class="page-link-simple {{ $histories->onFirstPage() ? 'disabled' : '' }}" href="{{ $histories->previousPageUrl() ?: '#' }}">Previous</a>
                <a class="page-link-simple {{ $histories->hasMorePages() ? '' : 'disabled' }}" href="{{ $histories->nextPageUrl() ?: '#' }}">Next</a>
            </div>
        </div>
    @endif
</div>
@endsection
