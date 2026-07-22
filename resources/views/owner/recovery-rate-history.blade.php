@extends('layouts.owner')

@section('content')
<style>
    .page-header { margin-bottom: 22px; display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; flex-wrap: wrap; }
    .page-title { font-size: 1.9rem; font-weight: 900; color: #0f172a; margin: 0 0 5px; }
    .page-subtitle { color: #64748b; font-size: 0.95rem; margin: 0; }
    .btn-back { display: inline-flex; background: #fff; border: 1px solid #d1d5db; color: #334155; padding: 10px 14px; border-radius: 11px; font-weight: 800; text-decoration: none; }
    .btn-back:hover { border-color: #2f5d1e; color: #2f5d1e; }
    .history-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 18px; padding: 22px 24px; box-shadow: 0 10px 26px rgba(15,23,42,.05); }
    .table-wrap { border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; }
    .table-scroll { overflow-x: auto; }
    .history-table { width: 100%; border-collapse: collapse; }
    .history-table th { background: #f8fafc; color: #475569; padding: 14px; text-align: left; font-size: .78rem; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
    .history-table td { padding: 14px; border-top: 1px solid #f1f5f9; color: #334155; white-space: nowrap; }
    .type-pill { background: #eff6ff; color: #1d4ed8; padding: 6px 10px; border-radius: 999px; font-weight: 900; }
    .rate-old { background: #fef2f2; color: #b91c1c; padding: 6px 10px; border-radius: 10px; font-weight: 800; }
    .rate-new { background: #ecfdf5; color: #047857; padding: 6px 10px; border-radius: 10px; font-weight: 800; }
    .date-text { color: #64748b; font-size: .85rem; font-weight: 700; }
    .empty-state { text-align: center; padding: 35px; color: #64748b; font-weight: 700; }
    .table-footer { margin-top: 16px; display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
    .footer-text { margin: 0; color: #64748b; font-size: .85rem; font-weight: 700; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Recovery Rate History</h1>
        <p class="page-subtitle">Complete history of rice-type recovery-rate changes.</p>
    </div>
    <a href="{{ route('owner.rice-types') }}" class="btn-back">← Back to Rice Types</a>
</div>

<div class="history-card">
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="history-table">
                <thead>
                    <tr><th>Rice Type</th><th>Old Rate</th><th>New Rate</th><th>Date &amp; Time</th></tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                        <tr>
                            <td><span class="type-pill">{{ $history->riceType->name }}</span></td>
                            <td><span class="rate-old">{{ number_format($history->old_rate, 2) }}%</span></td>
                            <td><span class="rate-new">{{ number_format($history->new_rate, 2) }}%</span></td>
                            <td><span class="date-text">{{ $history->changed_at->format('M d, Y h:i A') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state">No recovery rate changes recorded yet.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-footer">
        <p class="footer-text">Showing paginated recovery-rate history.</p>
        <div>{{ $histories->links() }}</div>
    </div>
</div>
@endsection
