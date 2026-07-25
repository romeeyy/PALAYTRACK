@extends('layouts.owner')

@section('content')
@include('owner.partials.history-styles')

<div class="audit-page-header">
    <div>
        <h1 class="audit-page-title">Milling Fee History</h1>
        <p class="audit-page-subtitle">Complete audit trail of all milling fee changes.</p>
    </div>
    <a href="{{ route('owner.settings') }}" class="audit-back">&larr; Back to Settings</a>
</div>

<div class="audit-card">
    <form method="GET" action="{{ route('owner.milling-fee-history') }}" class="audit-filter">
        <div class="audit-filter-group">
            <label class="audit-filter-label" for="milling_type">Filter by Milling Type</label>
            <select id="milling_type" name="milling_type" class="audit-filter-select">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                <option value="menudo" {{ $type === 'menudo' ? 'selected' : '' }}>Menudo</option>
                <option value="commercial" {{ $type === 'commercial' ? 'selected' : '' }}>Commercial</option>
            </select>
        </div>
        <div class="audit-filter-actions">
            <button type="submit" class="audit-button audit-button-primary">Apply Filter</button>
            <a href="{{ route('owner.milling-fee-history') }}" class="audit-button audit-button-secondary">Reset</a>
        </div>
    </form>

    <div class="audit-table-scroll">
        <table class="audit-table">
            <thead>
                <tr><th>Milling Type</th><th>Old Fee</th><th>New Fee</th><th>Date &amp; Time</th></tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td><span class="audit-pill audit-pill-type">{{ ucfirst($history->milling_type) }}</span></td>
                        <td><span class="audit-pill audit-pill-old">₱{{ number_format($history->old_fee, 2) }}/kg</span></td>
                        <td><span class="audit-pill audit-pill-new">₱{{ number_format($history->new_fee, 2) }}/kg</span></td>
                        <td><span class="audit-muted">{{ \Carbon\Carbon::parse($history->changed_at)->format('M d, Y h:i A') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="audit-empty">No milling fee history found for this filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('owner.partials.history-pagination', [
        'records' => $histories,
        'emptyText' => 'No milling fee history found for this filter.',
    ])
</div>
@endsection
