@extends('layouts.owner')

@section('content')
@include('owner.partials.history-styles')

<div class="audit-page-header">
    <div>
        <h1 class="audit-page-title">Client Change History</h1>
        <p class="audit-page-subtitle">Profile and classification changes made by the owner.</p>
    </div>
    <a class="audit-back" href="{{ route('owner.clients') }}">&larr; Back to Clients</a>
</div>

<div class="audit-card">
    <div class="audit-table-scroll">
        <table class="audit-table">
            <thead>
                <tr><th>Date &amp; Time</th><th>Client</th><th>Field</th><th>Old Value</th><th>New Value</th><th>Owner</th></tr>
            </thead>
            <tbody>
                @forelse($histories as $item)
                    <tr>
                        <td><span class="audit-muted">{{ $item->changed_at->format('M d, Y h:i A') }}</span></td>
                        <td>{{ $item->client->name ?? 'Unknown' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($item->field)) }}</td>
                        <td>{{ $item->old_value ?? '—' }}</td>
                        <td>{{ $item->new_value ?? '—' }}</td>
                        <td>{{ $item->owner->name ?? 'Owner' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="audit-empty">No client changes recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('owner.partials.history-pagination', [
        'records' => $histories,
        'emptyText' => 'No client changes recorded yet.',
    ])
</div>
@endsection
