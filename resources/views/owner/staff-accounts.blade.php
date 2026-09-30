@extends('layouts.owner')

@section('content')
<style>
    .staff-page-shell { max-width: 1220px; margin: 0 auto; }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 14px;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 4px;
    }

    .page-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
    }

    .add-btn {
        background: #15803d;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.85rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
    }

    .add-btn:hover {
        background: #166534;
        color: #fff;
    }

    .page-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        color: #065f46;
        padding: 11px 14px;
        border-radius: 10px;
        margin-bottom: 14px;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .staff-card {
        background: linear-gradient(135deg, #f9fdf9 0%, #eaf5ec 100%);
        border: 1px solid #b9d2bd;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 30px rgba(24, 65, 35, 0.10);
    }

    .staff-filters {
        display: grid;
        grid-template-columns: minmax(260px, 1fr) 190px 112px 92px;
        gap: 10px;
        margin: 0 0 14px;
        padding: 14px 16px;
        align-items: center;
        background: #eef7f0;
        border: 1px solid #cbdccf;
        border-radius: 16px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.045);
    }

    .staff-filter-input {
        min-height: 42px;
        border: 1px solid #dbe3ec;
        border-radius: 10px;
        padding: 8px 12px;
        background: #fff;
        font-weight: 600;
    }

    .filter-btn, .filter-reset, .history-link {
        min-height: 42px;
        border-radius: 10px;
        padding: 8px 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-weight: 800;
        font-size: .82rem;
        text-decoration: none;
    }

    .filter-btn { border: 0; background: #166534; color: #fff; }
    .filter-reset { border: 1px solid #cbd5e1; color: #475569; background: #fff; }
    .filter-reset:hover { color: #1e293b; background: #f8fafc; }
    .history-link { border: 1px solid #bbd7c0; color: #166534; background: #fff; }
    .history-link:hover { color: #14532d; background: #f0fdf4; }

    .pagination-row { padding: 12px 16px; border-top: 1px solid #eef2f7; display:flex; justify-content:space-between; align-items:center; gap:12px; color:#64748b; font-size:.82rem; }
    .pagination-actions { display:flex; gap:7px; }
    .page-link-simple { border:1px solid #dbe3ec; border-radius:8px; padding:6px 10px; color:#334155; text-decoration:none; font-weight:700; }
    .page-link-simple.disabled { opacity:.45; pointer-events:none; }

    .staff-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #cbdccf;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .staff-card-title {
        font-size: 1.15rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
    }

    .staff-card-subtitle {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.82rem;
    }

    .staff-count {
        padding: 7px 11px;
        border: 1px solid #dce8df;
        border-radius: 999px;
        background: #f3faf4;
        color: #187340;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .table-custom th {
        background: #edf5ef;
        color: #244c2d;
        font-size: 0.8rem;
        font-weight: 900;
        padding: 12px 20px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .table-custom td {
        padding: 15px 20px;
        border-bottom: 1px solid #dce9df;
        font-size: 0.88rem;
        color: #1f2937;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover {
        background: #e8f5ea;
    }

    .table-custom tbody tr:nth-child(even) {
        background: #f5fbf6;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .staff-name {
        font-weight: 800;
        color: #0f172a;
    }

    .staff-person { display:flex; align-items:center; gap:11px; }
    .staff-avatar { width:38px; height:38px; border-radius:12px; display:grid; place-items:center; flex:0 0 38px; background:linear-gradient(145deg,#dcfce7,#eef8e9); color:#166534; font-weight:900; font-size:.82rem; }
    .staff-meta { min-width:0; }

    .staff-email {
        display: block;
        margin-top: 2px;
        color: #475569;
        font-size: 0.86rem;
    }

    .date-main { color:#334155; font-weight:700; }
    .date-sub { display:block; color:#94a3b8; font-size:.75rem; margin-top:2px; }

    .role-badge,
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 4px 9px;
        line-height: 1;
        gap: 6px;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .role-badge {
        background: #eef2ff;
        color: #3730a3;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-wrap {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .action-form {
        margin: 0;
    }

    .btn-edit-custom,
    .btn-toggle-custom {
        border: none;
        border-radius: 8px;
        width: 132px;
        min-height: 38px;
        justify-content: center;
        padding: 6px 10px;
        font-size: 0.78rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        white-space: nowrap;
        line-height: 1;
    }

    .btn-edit-custom {
        background: #f8fafc;
        border: 1px solid #dbe3ec;
        color: #334155;
        width: 100px;
    }

    .btn-toggle-custom.active-btn {
        background: #fff7f7;
        border: 1px solid #f2caca;
        color: #b42318;
    }

    .btn-toggle-custom.inactive-btn {
        background: #ecfdf5;
        border: 1px solid #ccebd5;
        color: #047857;
    }

    .btn-edit-custom i,
    .btn-toggle-custom i,
    .add-btn i {
        width: 14px;
        height: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 28px 16px !important;
        color: #64748b;
        font-size: 0.86rem;
    }

    .empty-state strong {
        display: block;
        color: #0f172a;
        margin-bottom: 4px;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.55rem;
        }

        .table-custom th,
        .table-custom td {
            padding: 10px 12px;
        }

        .action-wrap {
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .staff-filters { grid-template-columns: 1fr; }
        .page-actions { width: 100%; }
        .page-actions .history-link,
        .page-actions .add-btn { flex: 1; }
    }
</style>

<div class="container-fluid px-0 staff-page-shell">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Staff</h1>
            <p class="page-subtitle">Manage staff access, update account details, and control account status.</p>
        </div>

        <div class="page-actions">
            <a class="history-link" href="{{ route('owner.staff-accounts.history') }}">
                <i data-lucide="history"></i>
                <span>History</span>
            </a>
            <a href="{{ route('owner.staff-accounts.create') }}" class="add-btn">
                <i data-lucide="user-plus"></i>
                <span>Add Staff</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success-custom">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="staff-filters">
        <input class="staff-filter-input" type="search" name="search" value="{{ $search }}" placeholder="Search staff name or email">
        <select class="staff-filter-input" name="status">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button class="filter-btn" type="submit"><i data-lucide="sliders-horizontal"></i> Filter</button>
        <a class="filter-reset" href="{{ route('owner.staff-accounts') }}"><i data-lucide="rotate-ccw"></i> Reset</a>
    </form>

    <div class="staff-card">
        <div class="staff-card-header">
            <div>
                <h2 class="staff-card-title">Staff Accounts</h2>
                <p class="staff-card-subtitle">Review staff access and account status.</p>
            </div>
            <span class="staff-count">{{ $staffAccounts->total() }} {{ $staffAccounts->total() === 1 ? 'account' : 'accounts' }}</span>
        </div>

        <div class="table-wrap">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th>Staff Member</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th style="width: 220px; text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($staffAccounts as $staff)
                        <tr>
                            <td>
                                <div class="staff-person">
                                    <div class="staff-avatar">{{ collect(explode(' ', $staff->name))->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('') }}</div>
                                    <div class="staff-meta">
                                        <div class="staff-name">{{ $staff->name }}</div>
                                        <span class="staff-email">{{ $staff->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="date-main">{{ $staff->created_at->format('M d, Y') }}</span>
                                <span class="date-sub">{{ $staff->created_at->format('h:i A') }}</span>
                            </td>

                            <td>
                                <span class="status-badge {{ $staff->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <div class="action-wrap">
                                    <a href="{{ route('owner.staff-accounts.edit', $staff->id) }}" class="btn-edit-custom">
                                        <i data-lucide="square-pen"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form method="POST" action="{{ route('owner.staff-accounts.toggle-status', $staff->id) }}" class="action-form"
                                        data-confirm-title="{{ $staff->is_active ? 'Deactivate Staff Account?' : 'Activate Staff Account?' }}"
                                        data-confirm-message="{{ $staff->is_active ? 'Are you sure you want to deactivate '.$staff->name.'? This account will no longer be able to access the system.' : 'Are you sure you want to activate '.$staff->name.'? This account will be able to access the system again.' }}"
                                        data-confirm-button="{{ $staff->is_active ? 'Deactivate' : 'Activate' }}"
                                        data-confirm-variant="{{ $staff->is_active ? 'danger' : 'success' }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="btn-toggle-custom {{ $staff->is_active ? 'active-btn' : 'inactive-btn' }}"
                                        >
                                            <i data-lucide="{{ $staff->is_active ? 'user-minus' : 'user-check' }}"></i>
                                            <span>{{ $staff->is_active ? 'Deactivate' : 'Activate' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                <strong>No staff accounts found</strong>
                                Add your first staff account to begin managing user access.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffAccounts->hasPages())
            <div class="pagination-row">
                <span>Showing {{ $staffAccounts->firstItem() }}–{{ $staffAccounts->lastItem() }} of {{ $staffAccounts->total() }}</span>
                <div class="pagination-actions">
                    <a class="page-link-simple {{ $staffAccounts->onFirstPage() ? 'disabled' : '' }}" href="{{ $staffAccounts->previousPageUrl() ?: '#' }}">Previous</a>
                    <a class="page-link-simple {{ $staffAccounts->hasMorePages() ? '' : 'disabled' }}" href="{{ $staffAccounts->nextPageUrl() ?: '#' }}">Next</a>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
