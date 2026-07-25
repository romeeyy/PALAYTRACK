@extends('layouts.owner')

@section('content')
@include('owner.partials.history-styles')

<div class="audit-page-header">
    <div>
        <h1 class="audit-page-title">Staff Account History</h1>
        <p class="audit-page-subtitle">Account creation, profile, password, and access-status changes.</p>
    </div>
    <a href="{{ route('owner.staff-accounts') }}" class="audit-back">&larr; Back to Staff Accounts</a>
</div>

<div class="audit-card">
    <div class="audit-table-scroll">
        <table class="audit-table">
            <thead>
                <tr><th>Date &amp; Time</th><th>Staff</th><th>Action</th><th>Field</th><th>Old Value</th><th>New Value</th><th>Owner</th></tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td><span class="audit-muted">{{ $history->changed_at->format('M d, Y h:i A') }}</span></td>
                        <td>{{ $history->staff->name ?? 'Unknown staff' }}</td>
                        <td><span class="audit-pill audit-pill-action">{{ str_replace('_', ' ', ucfirst($history->action)) }}</span></td>
                        <td>{{ $history->field ? ucfirst($history->field) : '—' }}</td>
                        <td>{{ $history->old_value ?? '—' }}</td>
                        <td>{{ $history->new_value ?? '—' }}</td>
                        <td>{{ $history->owner->name ?? 'Owner' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="audit-empty">No staff account changes recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="audit-footer">
        <span class="audit-footer-text">
            @if($histories->total())
                Showing {{ $histories->firstItem() }}–{{ $histories->lastItem() }} of {{ $histories->total() }}
            @else
                Complete staff account audit trail.
            @endif
        </span>
        @if($histories->hasPages())
            <div class="audit-pagination-actions">
                <a class="audit-page-link {{ $histories->onFirstPage() ? 'disabled' : '' }}" href="{{ $histories->previousPageUrl() ?: '#' }}">Previous</a>
                <a class="audit-page-link {{ $histories->hasMorePages() ? '' : 'disabled' }}" href="{{ $histories->nextPageUrl() ?: '#' }}">Next</a>
            </div>
        @endif
    </div>
</div>
@endsection
