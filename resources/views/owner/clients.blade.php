@extends('layouts.owner')

@section('content')
<style>
    .clients-shell { max-width: 1220px; margin: 0 auto; }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
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
        margin-top: 4px;
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

    .btn-filter,
    .btn-reset {
        min-height: 46px;
        border-radius: 12px;
        font-weight: 800;
        padding: 10px 18px;
    }

    .btn-filter {
        background: #2f5d1e;
        color: #fff;
        border: none;
    }

    .btn-filter:hover {
        background: #244915;
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
        padding: 14px 18px;
        white-space: nowrap;
    }

    .badge-regular {
        background: #fef3c7;
        color: #92400e;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .badge-commercial {
        background: #dcfce7;
        color: #166534;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 800;
    }

    .btn-save {
        border-radius: 10px;
        min-height: 42px;
        font-weight: 800;
        padding-left: 14px;
        padding-right: 14px;
    }

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
    .history-btn, .edit-btn { border:1px solid #bcd8c0; color:#166534; background:#fff; border-radius:11px; padding:9px 13px; font-weight:800; text-decoration:none; white-space:nowrap; }
    .edit-btn { padding:10px 12px; display:inline-flex; align-items:center; }
    .client-person { display:flex; align-items:center; gap:11px; }
    .client-avatar { width:38px; height:38px; border-radius:12px; display:grid; place-items:center; flex:0 0 38px; background:#edf8e9; color:#166534; font-size:.8rem; font-weight:900; }
    .client-name { font-weight:800; color:#0f172a; }
    .manage-form .custom-select { min-width:145px; }
    .content-card .table { margin-bottom:0; }
    .pagination-wrap { padding:12px 18px; border-top:1px solid #edf2f7; }
    .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;padding:16px;z-index:9999}.modal-overlay.show{display:flex}.client-modal{width:100%;max-width:520px;background:#fff;border-radius:18px;box-shadow:0 22px 55px rgba(15,23,42,.22);overflow:hidden}.modal-head{padding:19px 21px;border-bottom:1px solid #edf2f7;display:flex;justify-content:space-between;gap:12px}.modal-title{font-size:1.2rem;font-weight:900;margin:0 0 3px}.modal-sub{font-size:.84rem;color:#64748b;margin:0}.modal-close{border:0;background:#f1f5f9;width:34px;height:34px;border-radius:9px;font-size:1.25rem}.modal-body{padding:21px}.modal-field{margin-bottom:16px}.modal-field label{font-weight:800;font-size:.88rem;margin-bottom:7px}.modal-field input{min-height:48px;border-radius:11px}.modal-actions{display:flex;justify-content:flex-end;gap:9px;margin-top:20px}.modal-cancel,.modal-save{border-radius:10px;padding:10px 15px;font-weight:800}.modal-cancel{background:#fff;border:1px solid #d1d5db}.modal-save{background:#15803d;color:#fff;border:0}
    @media(max-width:768px){.page-title{font-size:1.65rem}.header-tools{width:100%}.manage-form{flex-wrap:wrap}}
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
        <div class="record-pill">Total Clients: {{ $clients->total() }}</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-4 shadow-sm border-0">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger rounded-4 shadow-sm border-0">
        {{ $errors->first() }}
    </div>
@endif

<div class="filter-card">
    <form method="GET" action="{{ route('owner.clients') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold">Search Client</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    class="form-control custom-input"
                    placeholder="Search by name or contact number..."
                >
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Client Type</label>
                <select name="client_type" class="form-select custom-select">
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

            <div class="col-md-4 d-flex gap-2">
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
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Contact Number</th>
                    <th>Current Type</th>
                    <th width="380">Manage Client</th>
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
                            @if($client->client_type === 'commercial')
                                <span class="badge-commercial">Commercial Partner</span>
                            @else
                                <span class="badge-regular">Regular Client</span>
                            @endif
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

                                <select name="client_type" class="form-select custom-select">
                                    <option value="regular" {{ $client->client_type === 'regular' ? 'selected' : '' }}>
                                        Regular
                                    </option>

                                    <option value="commercial" {{ $client->client_type === 'commercial' ? 'selected' : '' }}>
                                        Commercial
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-success btn-save">
                                    Save
                                </button>
                                <button type="button" class="edit-btn js-edit-client"
                                    data-name="{{ $client->name }}"
                                    data-contact="{{ $client->contact_number }}"
                                    data-action="{{ route('owner.clients.update', $client) }}">Edit</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No clients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div class="pagination-wrap">{{ $clients->links() }}</div>
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
            <div class="modal-body">
                <div class="modal-field"><label class="form-label" for="editClientName">Full Name</label><input id="editClientName" class="form-control" name="name" required maxlength="255"></div>
                <div class="modal-field"><label class="form-label" for="editClientContact">Contact Number</label><input id="editClientContact" class="form-control" name="contact_number" required placeholder="09XXXXXXXXX"></div>
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

    document.querySelectorAll('.js-edit-client').forEach(button => button.addEventListener('click', () => {
        editForm.action = button.dataset.action;
        editName.value = button.dataset.name;
        editContact.value = button.dataset.contact;
        editModal.classList.add('show');
        editModal.setAttribute('aria-hidden', 'false');
        editName.focus();
    }));
    document.querySelectorAll('.js-close-edit').forEach(button => button.addEventListener('click', () => editModal.classList.remove('show')));

    editModal.addEventListener('click', event => { if (event.target === editModal) editModal.classList.remove('show'); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') editModal.classList.remove('show'); });
</script>
@endsection
