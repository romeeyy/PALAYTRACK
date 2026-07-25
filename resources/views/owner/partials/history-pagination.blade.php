<div class="audit-footer">
    <span class="audit-footer-text">
        @if($records->total())
            Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }}
        @else
            {{ $emptyText ?? 'No history records found.' }}
        @endif
    </span>

    @if($records->hasPages())
        <div class="audit-pagination-actions">
            <a class="audit-page-link {{ $records->onFirstPage() ? 'disabled' : '' }}"
               href="{{ $records->previousPageUrl() ?: '#' }}">Previous</a>
            <a class="audit-page-link {{ $records->hasMorePages() ? '' : 'disabled' }}"
               href="{{ $records->nextPageUrl() ?: '#' }}">Next</a>
        </div>
    @endif
</div>
