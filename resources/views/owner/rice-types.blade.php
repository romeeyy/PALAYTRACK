@extends('layouts.owner')

@section('content')

@php
    $totalRiceTypes = $riceTypes->count();
    $activeRiceTypes = $riceTypes->where('status', 'active')->count();
    $inactiveRiceTypes = $riceTypes->where('status', 'inactive')->count();
@endphp

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 28px;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .btn-main {
        min-height: 52px;
        border-radius: 12px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #2f5d1e 0%, #3f7a28 100%);
        border: none;
        color: #fff;
        font-weight: 800;
        box-shadow: 0 10px 18px rgba(47, 93, 30, 0.18);
    }

    .btn-main:hover {
        background: linear-gradient(135deg, #274d19 0%, #35671f 100%);
        color: #fff;
        transform: translateY(-1px);
    }

    .summary-card {
        border: none;
        border-radius: 22px;
        padding: 22px 22px 20px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
        height: 100%;
        position: relative;
        overflow: hidden;
        border-top: 5px solid #15803d;
    }

    .summary-card.orange {
        background: linear-gradient(135deg, #ffffff 0%, #fffaf0 100%);
        border-top-color: #f59e0b;
    }

    .summary-card.gray {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-top-color: #64748b;
    }

    .summary-card::after {
        content: "";
        position: absolute;
        right: -35px;
        bottom: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(47, 93, 30, 0.08);
    }

    .summary-card.orange::after {
        background: rgba(245, 158, 11, 0.12);
    }

    .summary-card.gray::after {
        background: rgba(100, 116, 139, 0.10);
    }

    .summary-top,
    .summary-value,
    .summary-note {
        position: relative;
        z-index: 1;
    }

    .summary-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
    }

    .summary-label {
        font-size: 0.95rem;
        color: #334155;
        font-weight: 800;
        margin: 0;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eef6ea;
        color: #2f5d1e;
    }

    .summary-card.orange .summary-icon {
        background: #fff7ed;
        color: #ea580c;
    }

    .summary-card.gray .summary-icon {
        background: #eef2f7;
        color: #475569;
    }

    .summary-icon svg {
        width: 22px;
        height: 22px;
        stroke-width: 2.2;
    }

    .summary-value {
        font-size: 2.15rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        margin: 0 0 8px;
    }

    .summary-note {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    .section-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fbf7 100%);
        border: 1px solid #edf2f7;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .section-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .section-heading-row .section-subtitle {
        margin-bottom: 0;
    }

    .history-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #15803d;
        font-size: 0.86rem;
        font-weight: 800;
        text-decoration: none;
        padding: 7px 0;
        white-space: nowrap;
    }

    .history-link:hover {
        color: #166534;
        text-decoration: underline;
    }

    .history-link svg {
        width: 17px;
        height: 17px;
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 900;
        color: #111827;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .rice-table {
        margin-bottom: 0;
    }

    .rice-table thead th {
        background: #f8fafc;
        color: #334155;
        font-size: 0.92rem;
        font-weight: 800;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .rice-table tbody td {
        color: #334155;
        font-size: 0.95rem;
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f7;
    }

    .rice-table tbody tr:hover {
        background: #f8fafc;
    }

    .rice-table tbody tr:last-child td {
        border-bottom: none;
    }

    .rice-name {
        font-weight: 800;
        color: #0f172a;
    }

    .rate-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        background: #e8f9ef;
        color: #15803d;
        font-size: 0.88rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #eef2f7;
        color: #475569;
    }

    .action-btn {
        border-radius: 10px;
        padding: 8px 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        color: #64748b;
        padding: 28px 16px;
        font-size: 0.96rem;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 18px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 650px;
        background: #ffffff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
        animation: modalFade 0.2s ease;
    }

    @keyframes modalFade {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eef2f7;
    }

    .modal-title-custom {
        font-size: 1.45rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 4px;
    }

    .modal-subtitle-custom {
        color: #64748b;
        font-size: 0.94rem;
        margin: 0;
    }

    .close-modal-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #111827;
        font-size: 1.4rem;
        line-height: 1;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .close-modal-btn:hover {
        background: #f8fafc;
    }

    .field-label {
        font-size: 0.92rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #dfe7ef;
        background: #f8fafc;
        border-radius: 13px;
        min-height: 50px;
        padding: 12px 14px;
        color: #111827;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .custom-textarea {
        min-height: 95px;
        resize: vertical;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .help-text {
        font-size: 0.88rem;
        color: #64748b;
        margin-top: 7px;
    }

    .preview-box {
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 14px;
        padding: 15px 16px;
        min-height: 82px;
    }

    .preview-label {
        font-size: 0.88rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 5px;
    }

    .preview-value {
        font-size: 1.55rem;
        font-weight: 900;
        color: #15803d;
        line-height: 1.1;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-save {
        min-height: 48px;
        border-radius: 12px;
        padding: 11px 18px;
        background: #2f5d1e;
        border: none;
        color: #ffffff;
        font-weight: 800;
    }

    .btn-save:hover {
        background: #274d19;
        color: #ffffff;
    }

    .btn-cancel {
        min-height: 48px;
        border-radius: 12px;
        padding: 11px 18px;
        background: #ffffff;
        border: 1px solid #d1d5db;
        color: #111827;
        font-weight: 800;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #111827;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.75rem;
        }

        .section-card {
            padding: 18px;
        }

        .summary-value {
            font-size: 1.9rem;
        }

        .modal-box {
            padding: 22px 18px;
        }

        .modal-actions {
            justify-content: stretch;
        }

        .btn-save,
        .btn-cancel {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Rice Types</h1>
        <p class="page-subtitle">Manage rice varieties and assigned recovery rates.</p>
    </div>

    <div>
        <button type="button" class="btn btn-main d-flex align-items-center gap-2" onclick="openAddModal()">
            <i data-lucide="plus"></i>
            Add Rice Type
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="summary-card">
            <div class="summary-top">
                <p class="summary-label">Total Rice Types</p>
                <div class="summary-icon">
                    <i data-lucide="wheat"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $totalRiceTypes }}</h2>
            <p class="summary-note">Registered rice varieties</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-card orange">
            <div class="summary-top">
                <p class="summary-label">Active Rice Types</p>
                <div class="summary-icon">
                    <i data-lucide="check-circle"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $activeRiceTypes }}</h2>
            <p class="summary-note">Available for delivery records</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-card gray">
            <div class="summary-top">
                <p class="summary-label">Inactive Rice Types</p>
                <div class="summary-icon">
                    <i data-lucide="circle-off"></i>
                </div>
            </div>
            <h2 class="summary-value">{{ $inactiveRiceTypes }}</h2>
            <p class="summary-note">Hidden or not currently used</p>
        </div>
    </div>
</div>

<div class="section-card">
    <div class="section-heading-row">
        <div>
            <h2 class="section-title">Rice Type List</h2>
            <p class="section-subtitle">Recovery rates are used to estimate milled rice output during delivery recording.</p>
        </div>
        <a href="{{ route('owner.recovery-rate-history') }}" class="history-link">
            <i data-lucide="history"></i>
            View History
        </a>
    </div>

    <div class="table-responsive">
        <table class="table rice-table align-middle">
            <thead>
                <tr>
                    <th>Rice Type / Variety</th>
                    <th>Recovery Rate (%)</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riceTypes as $riceType)
                    <tr>
                        <td class="rice-name">{{ $riceType->name }}</td>

                        <td>
                            <span class="rate-badge">
                                {{ number_format($riceType->recovery_rate, 2) }}%
                            </span>
                        </td>

                        <td>{{ $riceType->description ?? 'No description' }}</td>

                        <td>
                            <span class="status-badge {{ $riceType->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                {{ ucfirst($riceType->status) }}
                            </span>
                        </td>

                        <td>
                           <button
    type="button"
    class="btn btn-outline-success btn-sm action-btn"
    onclick="openEditModal(this)"
    data-id="{{ $riceType->id }}"
    data-name="{{ $riceType->name }}"
    data-recovery-rate="{{ $riceType->recovery_rate }}"
    data-status="{{ $riceType->status }}"
    data-description="{{ $riceType->description }}"
>
    Edit
</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            No rice types found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="addRiceTypeModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header-custom">
            <div>
                <h2 class="modal-title-custom">Add Rice Type</h2>
                <p class="modal-subtitle-custom">Create a new rice variety and assign its recovery rate.</p>
            </div>

            <button type="button" class="close-modal-btn" onclick="closeAddModal()">×</button>
        </div>

        <form method="POST" action="/owner/add-rice-type">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="field-label">Rice Type / Variety Name *</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control custom-input"
                        placeholder="Enter rice type name"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) *</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="100"
                        id="addRecoveryRate"
                        name="recovery_rate"
                        class="form-control custom-input"
                        placeholder="Enter recovery rate"
                        required
                    >
                    <div class="help-text">Used to estimate milled rice output.</div>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Status *</label>
                    <select name="status" class="form-select custom-select" required>
                        <option value="">Select status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Typical Milling Output Preview</label>
                    <div class="preview-box">
                        <div class="preview-label">Based on 100 kg of palay</div>
                        <div id="addPreviewOutput" class="preview-value">0.00 kg</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control custom-textarea"
                        placeholder="Describe this rice type or variety"
                    ></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-save">
                    Save Rice Type
                </button>

                <button type="button" class="btn btn-cancel" onclick="closeAddModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editRiceTypeModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header-custom">
            <div>
                <h2 class="modal-title-custom">Edit Rice Type</h2>
                <p class="modal-subtitle-custom">Update rice type details without leaving this page.</p>
            </div>

            <button type="button" class="close-modal-btn" onclick="closeEditModal()">×</button>
        </div>

        <form id="editRiceTypeForm" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="field-label">Rice Type / Variety Name *</label>
                    <input
                        type="text"
                        id="editName"
                        name="name"
                        class="form-control custom-input"
                        placeholder="Enter rice type name"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) *</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="100"
                        id="editRecoveryRate"
                        name="recovery_rate"
                        class="form-control custom-input"
                        placeholder="Enter recovery rate"
                        required
                    >
                    <div class="help-text">Used to estimate milled rice output.</div>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Status *</label>
                    <select id="editStatus" name="status" class="form-select custom-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Typical Milling Output Preview</label>
                    <div class="preview-box">
                        <div class="preview-label">Based on 100 kg of palay</div>
                        <div id="editPreviewOutput" class="preview-value">0.00 kg</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Description</label>
                    <textarea
                        id="editDescription"
                        name="description"
                        class="form-control custom-textarea"
                        placeholder="Describe this rice type or variety"
                    ></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn btn-save">
                    Save Changes
                </button>

                <button type="button" class="btn btn-cancel" onclick="closeEditModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const addModal = document.getElementById('addRiceTypeModal');
    const editModal = document.getElementById('editRiceTypeModal');

    const addRecoveryRate = document.getElementById('addRecoveryRate');
    const addPreviewOutput = document.getElementById('addPreviewOutput');

    const editForm = document.getElementById('editRiceTypeForm');
    const editRecoveryRate = document.getElementById('editRecoveryRate');
    const editPreviewOutput = document.getElementById('editPreviewOutput');

    function openAddModal() {
        addModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        updateAddPreview();
    }

    function closeAddModal() {
        addModal.classList.remove('show');
        document.body.style.overflow = '';
    }

    function openEditModal(riceType) {
        editForm.action = '/owner/edit-rice-type/' + riceType.id;

        document.getElementById('editName').value = riceType.name || '';
        document.getElementById('editRecoveryRate').value = riceType.recovery_rate || '';
        document.getElementById('editStatus').value = riceType.status || 'active';
        document.getElementById('editDescription').value = riceType.description || '';

        updateEditPreview();

        editModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        editModal.classList.remove('show');
        document.body.style.overflow = '';
    }

    function updateAddPreview() {
        const rate = parseFloat(addRecoveryRate.value) || 0;
        const result = 100 * (rate / 100);

        addPreviewOutput.textContent = result.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' kg';
    }

    function updateEditPreview() {
        const rate = parseFloat(editRecoveryRate.value) || 0;
        const result = 100 * (rate / 100);

        editPreviewOutput.textContent = result.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' kg';
    }

    addRecoveryRate.addEventListener('input', updateAddPreview);
    editRecoveryRate.addEventListener('input', updateEditPreview);

    addModal.addEventListener('click', function(event) {
        if (event.target === addModal) {
            closeAddModal();
        }
    });

    editModal.addEventListener('click', function(event) {
        if (event.target === editModal) {
            closeEditModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>

@endsection
