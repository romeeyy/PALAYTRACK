@extends('layouts.owner')

@section('content')
<style>
    .clients-shell { max-width: 1220px; margin: 0 auto; }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .page-subtitle {
        color: #6b7280;
        margin: 3px 0 0;
        line-height: 1.35;
    }

    .content-card {
        background: #fff;
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
        border: 1px solid #e3e9e1;
    }

    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 18px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.045);
        border: 1px solid #e5ebe4;
        margin-bottom: 14px;
    }

    .custom-input,
    .custom-select {
        border-radius: 12px;
        min-height: 46px;
        border: 1px solid #dbe3ec;
        font-weight: 600;
    }

    .client-filter-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(180px, 280px) auto;
        gap: 16px;
        align-items: end;
    }
    .client-filter-grid > div { min-width: 0; }
    .client-filter-grid .form-label { font-size: .875rem; margin-bottom: 8px; }
    .client-filter-grid :is(.custom-input, .custom-select) { height: 46px; font-weight: 400; }
    .client-filter-actions { display: flex; gap: 8px; }
    .client-filter-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 46px;
        min-width: 88px;
        font-size: .9rem;
        font-weight: 600;
    }
    @media (max-width: 1100px) {
        .client-filter-grid { grid-template-columns: minmax(0, 1fr) minmax(160px, .6fr); }
        .client-filter-actions { grid-column: 1 / -1; justify-content: flex-end; }
    }
    @media (max-width: 575px) {
        .client-filter-grid { grid-template-columns: minmax(0, 1fr); gap: 12px; }
        .client-filter-actions .btn { flex: 1; }
    }

    .btn-filter,
    .btn-reset {
        min-height: 46px;
        border-radius: 12px;
        font-weight: 800;
        padding: 10px 18px;
    }

    .btn-filter {
        background: var(--user-accent, #2f5d1e);
        color: #fff;
        border: none;
    }

    .btn-filter:hover {
        background: var(--user-accent-dark, #244915);
        color: #fff;
    }

    .table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        font-weight: 800;
        padding: 13px 18px;
        white-space: nowrap;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 10px 18px;
        white-space: nowrap;
    }

    .badge-regular {
        background: #fef3c7;
        color: #92400e;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .badge-commercial {
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .btn-save {
        border-radius: 9px;
        min-height: 38px;
        font-weight: 800;
        padding-left: 12px;
        padding-right: 12px;
        font-size: .9rem;
        transition: opacity .2s ease, transform .2s ease;
    }

    .btn-save.is-hidden { display: none; }

    .record-pill {
        padding: 10px 14px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 800;
    }

    .header-tools { display:flex; align-items:center; gap:10px; }
    .history-btn, .edit-btn { border:1px solid color-mix(in srgb, var(--user-accent, #168344) 35%, #fff); color:var(--user-accent-dark, #166534); background:#fff; border-radius:11px; padding:9px 13px; font-weight:800; text-decoration:none; white-space:nowrap; }
    .edit-btn { min-height:38px; padding:8px 11px; display:inline-flex; align-items:center; font-size:.9rem; }
    .client-person { display:flex; align-items:center; gap:10px; }
    .client-avatar { width:34px; height:34px; border-radius:10px; display:grid; place-items:center; flex:0 0 34px; background:var(--user-accent-soft, #edf8e9); color:var(--user-accent-dark, #166534); font-size:.75rem; font-weight:900; }
    .client-name { font-weight:800; color:#0f172a; }
    .manage-form .custom-select { min-width:145px; min-height:40px; padding-top:6px; padding-bottom:6px; font-size:.92rem; }
    .content-card .table { margin-bottom:0; }
    .table-heading { padding: 18px 22px 14px; border-bottom: 1px solid #edf2f7; display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .table-heading h2 { margin:0 0 3px; font-size:1.15rem; font-weight:900; color:#0f172a; }
    .table-heading p { margin:0; color:#64748b; font-size:.9rem; }
    .table-count { border:1px solid color-mix(in srgb, var(--user-accent, #168344) 24%, #fff); background:var(--user-accent-soft, #f8fbf8); color:var(--user-accent-dark, #166534); border-radius:999px; padding:7px 11px; font-size:.8rem; font-weight:800; white-space:nowrap; }
    .type-cell { display:flex; align-items:center; }
    .manage-form { justify-content:flex-start; gap:6px !important; }
    .manage-form .custom-select { flex:0 0 150px; width:150px; min-width:150px; }
    .manage-form .btn-save { min-width:62px; }
    .pagination-wrap { padding:12px 18px; border-top:1px solid #edf2f7; }
    .pagination-wrap nav { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
    .pagination-wrap .pagination { margin:0; gap:5px; }
    .pagination-wrap .page-link { min-width:36px; min-height:36px; display:grid; place-items:center; border-radius:9px; color:var(--user-accent-dark, #166534); background:#fff; border:1px solid color-mix(in srgb, var(--user-accent, #168344) 22%, #e5e7eb); font-weight:700; }
    .pagination-wrap .page-link:hover { background:var(--user-accent-soft, #eef8ee); border-color:var(--user-accent, #168344); color:var(--user-accent-dark, #166534); }
    .pagination-wrap .page-item.active .page-link { background:var(--user-accent, #15803d); border-color:var(--user-accent, #15803d); color:#fff; }
    .pagination-wrap .page-item.disabled .page-link { background:#f1f5f9; border-color:#e2e8f0; color:#94a3b8; }
    .client-empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:7px; min-height:154px; padding:24px 16px; text-align:center; color:#64748b; }
    .client-empty-icon { width:42px; height:42px; display:grid; place-items:center; border-radius:13px; background:var(--user-accent-soft,#edf8e9); color:var(--user-accent-dark,#166534); }
    .client-empty-icon svg { width:21px; height:21px; }
    .client-empty-title { margin:0; color:#1e293b; font-size:.98rem; font-weight:850; }
    .client-empty-text { margin:0; max-width:360px; font-size:.86rem; line-height:1.45; }
    .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;padding:16px;z-index:9999}.modal-overlay.show{display:flex}.client-modal{width:100%;max-width:520px;background:#fff;border-radius:18px;box-shadow:0 22px 55px rgba(15,23,42,.22);overflow:hidden}.modal-head{padding:19px 21px;border-bottom:1px solid #edf2f7;display:flex;justify-content:space-between;gap:12px}.modal-title{font-size:1.2rem;font-weight:900;margin:0 0 3px}.modal-sub{font-size:.84rem;color:#64748b;margin:0}.modal-close{border:0;background:#f1f5f9;width:34px;height:34px;border-radius:9px;font-size:1.25rem}.modal-body{padding:21px}.modal-field{margin-bottom:16px}.modal-field label{font-weight:800;font-size:.88rem;margin-bottom:7px}.modal-field input{min-height:48px;border-radius:11px}.modal-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:20px}.modal-cancel,.modal-save{border-radius:10px;padding:10px 15px;font-weight:800}.modal-cancel{background:#fff;border:1px solid #d1d5db}.modal-save{background:#15803d;color:#fff;border:0}
    @media(max-width:768px){.page-title{font-size:1.65rem}.header-tools{width:100%}.manage-form{flex-wrap:wrap}}
    @media(max-width:700px){.table-heading{align-items:flex-start; flex-direction:column}.table-heading h2{font-size:1.05rem}.table-heading p{font-size:.84rem}}
    .modal-field input.is-invalid{border-color:#ef4444;background:#fffafa;box-shadow:0 0 0 .16rem rgba(239,68,68,.08)}
    .modal-field .invalid-feedback{display:block;margin-top:6px;color:#b42318;font-size:.8rem;font-weight:600}
</style>

<div class="clients-shell">
<div class="page-header">
    <div>
        <h1 class="page-title">Client Management</h1>
        <p class="page-subtitle">
            Search, filter, and manage client classifications for automatic milling pricing.
        </p>
    </div>

    <div class="header-tools">
        <a class="history-btn" href="{{ route('owner.clients.history') }}">View History</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 shadow-sm border-0">
        {{ session('success') }}
    </div>
@endif

<div class="filter-card">
    <form method="GET" action="{{ route('owner.clients') }}">
        <div class="client-filter-grid">
            <div>
                <label for="client-search" class="form-label fw-bold">Search Client</label>
                <input
                    id="client-search"
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    class="form-control custom-input"
                    placeholder="Search by name or contact number..."
                >
            </div>

            <div>
                <label for="client-type-filter" class="form-label fw-bold">Client Type</label>
                <select id="client-type-filter" name="client_type" class="form-select custom-select">
                    <option value="all" {{ ($type ?? 'all') === 'all' ? 'selected' : '' }}>
                        All Types
                    </option>
                    <option value="regular" {{ ($type ?? '') === 'regular' ? 'selected' : '' }}>
                        Regular
                    </option>
                    <option value="commercial" {{ ($type ?? '') === 'commercial' ? 'selected' : '' }}>
                        Commercial
                    </option>
                </select>
            </div>

            <div class="client-filter-actions">
                <button type="submit" class="btn btn-filter">
                    Filter
                </button>

                <a href="{{ route('owner.clients') }}" class="btn btn-outline-secondary btn-reset">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<div class="content-card">
    <div class="table-heading">
        <div>
            <h2>Client List</h2>
            <p>Review client classifications and update pricing categories.</p>
        </div>
        <span class="table-count">{{ $clients->total() }} clients</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Contact Number</th>
                    <th>Current Type</th>
                    <th width="300">Update Client Type</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>
                            <div class="client-person">
                                <div class="client-avatar">{{ collect(explode(' ', $client->name))->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('') }}</div>
                                <span class="client-name">{{ $client->name }}</span>
                            </div>
                        </td>

                        <td>
                            {{ $client->contact_number ?? 'N/A' }}
                        </td>

                        <td>
                            <div class="type-cell">
                                @if($client->client_type === 'commercial')
                                    <span class="badge-commercial">Commercial Partner</span>
                                @else
                                    <span class="badge-regular">Regular Client</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <form
                                method="POST"
                                action="{{ route('owner.clients.update-type', $client->id) }}"
                                class="d-flex gap-2 align-items-center manage-form"
                                data-confirm-title="Confirm Changes"
                                data-confirm-message="Change this client classification? This will apply to future deliveries only."
                                data-confirm-button="Save Changes"
                            >
                                @csrf

                                <select name="client_type" class="form-select custom-select js-client-type" data-initial-type="{{ $client->client_type }}" aria-label="Change client type for {{ $client->name }}">
                                    <option value="regular" {{ $client->client_type === 'regular' ? 'selected' : '' }}>
                                        Regular
                                    </option>

                                    <option value="commercial" {{ $client->client_type === 'commercial' ? 'selected' : '' }}>
                                        Commercial
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-success btn-save is-hidden">
                                    Save
                                </button>
                                <button type="button" class="edit-btn js-edit-client"
                                    data-client-id="{{ $client->id }}"
                                    data-name="{{ $client->name }}"
                                    data-contact="{{ $client->contact_number }}"
                                    data-action="{{ route('owner.clients.update', $client) }}">Edit</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-0">
                            <div class="client-empty-state">
                                <div class="client-empty-icon" aria-hidden="true"><i data-lucide="users-round"></i></div>
                                <p class="client-empty-title">No clients found</p>
                                <p class="client-empty-text">Clients will appear here after they are included in a delivery record.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div class="pagination-wrap">{{ $clients->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
</div>

<div id="editClientModal" class="modal-overlay" aria-hidden="true">
    <div class="client-modal" role="dialog" aria-modal="true" aria-labelledby="editClientTitle">
        <div class="modal-head"><div><h2 id="editClientTitle" class="modal-title">Edit Client</h2><p class="modal-sub">Update the master profile. Old delivery records stay unchanged.</p></div><button type="button" class="modal-close js-close-edit">&times;</button></div>
        <form id="editClientForm" method="POST"
            data-confirm-title="Confirm Changes"
            data-confirm-message="Save these client profile changes?"
            data-confirm-button="Save Changes">
            @csrf
            <input type="hidden" id="editClientId" name="_edit_client_id" value="{{ old('_edit_client_id') }}">
            <div class="modal-body">
                <div class="modal-field"><label class="form-label" for="editClientName">Full Name</label><input id="editClientName" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required maxlength="255">@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="modal-field"><label class="form-label" for="editClientContact">Contact Number</label><input id="editClientContact" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" value="{{ old('contact_number') }}" required placeholder="09XXXXXXXXX">@error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="modal-actions"><button type="button" class="modal-cancel js-close-edit">Cancel</button><button type="submit" class="modal-save">Save Changes</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    const editModal = document.getElementById('editClientModal');
    const editForm = document.getElementById('editClientForm');
    const editName = document.getElementById('editClientName');
    const editContact = document.getElementById('editClientContact');
    const editClientId = document.getElementById('editClientId');

    document.querySelectorAll('.js-edit-client').forEach(button => button.addEventListener('click', () => {
        editForm.action = button.dataset.action;
        editClientId.value = button.dataset.clientId;
        editName.value = button.dataset.name;
        editContact.value = button.dataset.contact;
        editModal.classList.add('show');
        editModal.setAttribute('aria-hidden', 'false');
        editName.focus();
    }));
    @if(old('_edit_client_id') && $errors->any())
        editForm.action = @json(route('owner.clients.update', old('_edit_client_id')));
        editModal.classList.add('show');
        editModal.setAttribute('aria-hidden', 'false');
        window.setTimeout(() => document.querySelector('#editClientModal .is-invalid')?.focus(), 100);
    @endif
    document.querySelectorAll('.js-close-edit').forEach(button => button.addEventListener('click', () => editModal.classList.remove('show')));

    document.querySelectorAll('.js-client-type').forEach(select => {
        const saveButton = select.closest('form')?.querySelector('.btn-save');
        const syncSaveState = () => {
            if (!saveButton) return;
            saveButton.classList.toggle('is-hidden', select.value === select.dataset.initialType);
        };
        select.addEventListener('change', syncSaveState);
        syncSaveState();
    });

    editModal.addEventListener('click', event => { if (event.target === editModal) editModal.classList.remove('show'); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') editModal.classList.remove('show'); });
</script>
@endsection
