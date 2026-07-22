@extends('layouts.owner')
@section('content')
<style>
    .history-shell{max-width:1180px;margin:0 auto}.history-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}.history-title{font-size:1.9rem;font-weight:900;margin:0 0 4px}.history-sub{color:#64748b;margin:0}.back{border:1px solid #bcd8c0;color:#166534;padding:9px 13px;border-radius:10px;text-decoration:none;font-weight:800}.history-card{background:#fff;border:1px solid #e3e9e1;border-top:4px solid #2f7d32;border-radius:20px;overflow:hidden;box-shadow:0 14px 35px rgba(15,23,42,.06)}table{width:100%;border-collapse:collapse}th{background:#f7f9f8;color:#475569;padding:13px 16px;font-size:.8rem}td{padding:14px 16px;border-top:1px solid #eef2f7;font-size:.86rem}.empty{text-align:center;color:#64748b;padding:30px}.pages{padding:14px 16px}
</style>
<div class="history-shell"><div class="history-head"><div><h1 class="history-title">Client Change History</h1><p class="history-sub">Profile and classification changes made by the owner.</p></div><a class="back" href="{{ route('owner.clients') }}">Back to Clients</a></div>
<div class="history-card"><div class="table-responsive"><table><thead><tr><th>Date & Time</th><th>Client</th><th>Field</th><th>Old Value</th><th>New Value</th><th>Owner</th></tr></thead><tbody>
@forelse($histories as $item)<tr><td>{{ $item->changed_at->format('M d, Y h:i A') }}</td><td>{{ $item->client->name ?? 'Unknown' }}</td><td>{{ str_replace('_',' ',ucfirst($item->field)) }}</td><td>{{ $item->old_value ?? '—' }}</td><td>{{ $item->new_value ?? '—' }}</td><td>{{ $item->owner->name ?? 'Owner' }}</td></tr>@empty<tr><td colspan="6" class="empty">No client changes recorded yet.</td></tr>@endforelse
</tbody></table></div><div class="pages">{{ $histories->links() }}</div></div></div>
@endsection
