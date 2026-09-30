@extends('layouts.owner')

@section('content')
@include('owner.partials.history-styles')

<div class="audit-page-header">
    <div>
        <h1 class="audit-page-title">Recovery Rate History</h1>
        <p class="audit-page-subtitle">Complete history of rice-type recovery-rate changes.</p>
    </div>
    <a href="{{ route('owner.rice-types') }}" class="audit-back">&larr; Back to Rice Types</a>
</div>

<div class="audit-card">
    <form method="GET" action="{{ route('owner.recovery-rate-history') }}" class="audit-filter">
        <div class="audit-filter-group">
            <label class="audit-filter-label" for="rice_type_id">Filter by Rice Type</label>
            <select id="rice_type_id" name="rice_type_id" class="audit-filter-select">
                <option value="all" {{ $riceTypeId === 'all' ? 'selected' : '' }}>All Rice Types</option>
                @foreach($riceTypes as $riceType)
                    <option value="{{ $riceType->id }}" {{ (string) $riceTypeId === (string) $riceType->id ? 'selected' : '' }}>
                        {{ $riceType->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="audit-filter-actions">
            <button type="submit" class="audit-button audit-button-primary"><i data-lucide="sliders-horizontal" aria-hidden="true"></i>Apply Filter</button>
            <a href="{{ route('owner.recovery-rate-history') }}" class="audit-button audit-button-secondary"><i data-lucide="rotate-ccw" aria-hidden="true"></i>Reset</a>
        </div>
    </form>

    <div class="audit-table-scroll">
        <table class="audit-table">
            <thead>
                <tr><th>Rice Type</th><th>Old Rate</th><th>New Rate</th><th>Date &amp; Time</th></tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td><span class="audit-pill audit-pill-type">{{ $history->riceType->name }}</span></td>
                        <td><span class="audit-pill audit-pill-old">{{ number_format($history->old_rate, 2) }}%</span></td>
                        <td><span class="audit-pill audit-pill-new">{{ number_format($history->new_rate, 2) }}%</span></td>
                        <td><span class="audit-muted">{{ $history->changed_at->format('M d, Y h:i A') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="audit-empty">No recovery rate changes recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('owner.partials.history-pagination', [
        'records' => $histories,
        'emptyText' => 'No recovery rate history found for this filter.',
    ])
</div>
@endsection
