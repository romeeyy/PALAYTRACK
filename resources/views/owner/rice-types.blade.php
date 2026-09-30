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
        margin-bottom: 10px;
    }

    .page-title {
        margin: 0 0 4px;
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #64748b;
        margin: 0;
        font-size: .9rem;
    }

    .page-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-main,
    .history-link {
        min-height: 38px;
        padding: 8px 13px;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 700;
    }

    .btn-main {
        background: var(--user-accent, #15803d);
        border: 1px solid var(--user-accent, #15803d);
        color: #fff;
    }

    .btn-main:hover {
        background: var(--user-accent-dark, #166534);
        color: #fff;
    }

    .history-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #dce5df;
        color: #166534;
        text-decoration: none;
    }

    .history-link:hover {
        background: #f0f7f2;
        color: #14532d;
    }

    .page-actions svg {
        width: 18px;
        height: 18px;
    }

    .summary-card {
        position: relative;
        height: 100%;
        padding: 14px 16px 12px;
        border: none;
        border-radius: 20px;
        background: linear-gradient(135deg, #ffffff 0%, #f4faf3 100%);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        min-height: 124px;
    }

    .summary-card.green {
        background: linear-gradient(135deg, #ffffff 0%, #f1faf2 100%);
    }

    .summary-card.orange {
        background: linear-gradient(135deg, #ffffff 0%, #fffaf1 100%);
    }

    .summary-card.yellow {
        background: linear-gradient(135deg, #ffffff 0%, #fffdf1 100%);
    }

    .summary-card.gray {
        background: linear-gradient(135deg, #ffffff 0%, #f6f8fb 100%);
    }

    .summary-card:hover,
    .summary-card:active {
        transform: translateY(-4px);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.10);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        right: -32px;
        bottom: -32px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(47, 93, 30, 0.08);
        z-index: 0;
    }

    .summary-card.orange::after {
        background: rgba(245, 158, 11, 0.12);
    }

    .summary-card.yellow::after {
        background: rgba(234, 179, 8, 0.12);
    }

    .summary-card.gray::after {
        background: rgba(100, 116, 139, 0.1);
    }

    .summary-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .summary-label {
        margin: 0;
        font-size: 1.04rem;
        font-weight: 800;
        line-height: 1.3;
        color: #0f172a;
    }

    .summary-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #eaf5eb;
        color: #2e7d32;
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }

    .summary-card.orange .summary-icon {
        background: #fff1e3;
        color: #f97316;
    }

    .summary-card.yellow .summary-icon {
        background: #fef3c7;
        color: #d97706;
    }

    .summary-card.gray .summary-icon {
        background: #eef2f7;
        color: #64748b;
    }

    .summary-icon svg {
        width: 18px;
        height: 18px;
    }

    .main-content .summary-card .summary-value {
        margin: 0;
        font-size: 2.35rem;
        font-weight: 900;
        line-height: 1.1;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
        position: relative;
        z-index: 1;
    }

    .summary-note {
        margin: 10px 0 0;
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.4;
        position: relative;
        z-index: 1;
    }

    .summary-unit {
        font-size: 1rem;
        font-weight: 700;
        color: #475569;
    }

    .section-card {
        background: #fff;
        border: 1px solid #e2e8e4;
        border-radius: 20px;
        padding: 18px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
    }

    .section-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .section-heading-main { display: flex; align-items: flex-start; gap: 10px; }
    .section-heading-icon { display: grid; place-items: center; width: 34px; height: 34px; flex: 0 0 34px; border-radius: 10px; background: var(--user-accent-soft, #ecfdf5); color: var(--user-accent-dark, #166534); }
    .section-heading-icon svg { width: 17px; height: 17px; }
    .rice-list-card { padding: 14px 16px; }
    .rice-list-card .section-heading-row { margin-bottom: 8px; }
    .rice-list-card .section-heading-icon { width: 30px; height: 30px; flex-basis: 30px; border-radius: 9px; }
    .rice-list-card .section-heading-icon svg { width: 15px; height: 15px; }
    .rice-list-card .section-title { font-size: 1.15rem; margin-bottom: 3px; }
    .rice-list-card .section-subtitle { font-size: .84rem; }
    .rice-list-card .list-count { padding: 6px 11px; align-self: flex-start; margin: 1px 32px 0 auto; border: 1px solid #dbe3ec; border-radius: 999px; background: #f8fafc; color: #64748b; }

    .main-content .section-card .section-title {
        font-size: 1.35rem;
        font-weight: 900;
        margin: 0 0 6px;
        letter-spacing: -.015em;
        color: #0f172a;
    }

    .section-subtitle {
        margin: 0;
        color: #64748b;
        font-size: .9rem;
    }

    .list-count {
        padding: 6px 10px;
        border: 1px solid #e2e8e4;
        border-radius: 8px;
        color: #64748b;
        font-size: .75rem;
        white-space: nowrap;
    }

    .rice-table {
        margin-bottom: 0;
        min-width: 720px;
    }

    .rice-table thead th {
        padding: 12px 16px;
        background: #f7f9f8;
        color: #64748b;
        font-size: .75rem;
        font-weight: 700;
        border-bottom: 1px solid #e2e8e4;
        white-space: nowrap;
    }

    .rice-table tbody td {
        padding: 14px 12px;
        font-size: .875rem;
        color: #334155;
        vertical-align: middle;
        border-bottom: 1px solid #edf1ee;
    }

    .rice-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .rice-table tbody tr:hover>td {
        background-color: #f8fbf8;
    }

    /* Modern visual treatment */
    .summary-card {
        border: 1px solid #cbdccf;
        border-top: none;
        border-radius: 18px;
        padding: 14px 16px 12px;
        box-shadow: 0 14px 30px rgba(24, 65, 35, .10);
    }

    .summary-card.green { background: linear-gradient(135deg, #fbfefb 0%, #eaf7ec 100%); }
    .summary-card.orange { background: linear-gradient(135deg, #fffdf8 0%, #fff5dc 100%); }
    .summary-card.yellow { background: linear-gradient(135deg, #fffef5 0%, #fff8d9 100%); }
    .summary-card.gray { background: linear-gradient(135deg, #fbfcfe 0%, #edf1f6 100%); }
    .summary-card.green,
    .summary-card.orange,
    .summary-card.yellow,
    .summary-card.gray { border-top: none; }
    .summary-card::after { display: block; }
    .summary-card:hover,
    .summary-card:active { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(15, 23, 42, .08); }
    .summary-icon { width: 42px; height: 42px; border-radius: 12px; }
    .summary-icon { border: 0; box-shadow: none; }
    .summary-card.green .summary-value,
    .summary-card.gray .summary-value { color: #0f172a; }

    .section-card {
        border-radius: 18px;
        border-color: #b9d2bd;
        background: linear-gradient(135deg, #f9fdf9 0%, #eaf5ec 100%);
        box-shadow: 0 14px 30px rgba(24, 65, 35, .10);
    }

    .rice-table thead th { background: #edf5ef; color: #244c2d; font-size: .82rem; font-weight: 800; }
    .rice-table thead th:first-child { border-top-left-radius: 10px; }
    .rice-table thead th:last-child { border-top-right-radius: 10px; }
    .rice-table tbody td { background: rgba(255,255,255,.44); }
    .rice-table tbody tr:nth-child(even) td { background: rgba(245,251,246,.72); }

    .variety-cell {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .variety-icon {
        display: grid;
        place-items: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border: 1px solid #dfe9df;
        background: #f4f8f2;
        color: #527044;
        border-radius: 10px;
        box-shadow: none;
    }

    .variety-icon svg {
        width: 16px;
        height: 16px;
    }

    .rice-name {
        font-weight: 700;
        color: #0f172a;
        overflow-wrap: anywhere;
        letter-spacing: -0.01em;
    }

    .rate-display {
        width: 140px;
        max-width: 100%;
    }

    .rate-value {
        display: block;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        margin-bottom: 7px;
        color: #0f172a;
    }

    .rate-track {
        height: 6px;
        border-radius: 999px;
        background: #e9efea;
        overflow: hidden;
    }

    .rate-fill {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #7aa866 0%, #5e8d51 100%);
        border-radius: inherit;
    }

    .rice-table .description-cell {
        max-width: 280px;
        color: #64748b;
        overflow-wrap: anywhere;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    .status-active {
        background: #edf8f0;
        color: #187340;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .rice-table .actions-cell {
        text-align: center;
        width: 165px;
        min-width: 165px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border-radius: 10px;
        width: 90px;
        min-width: 90px;
        min-height: 36px;
        padding: 6px 10px;
        font-size: .8125rem;
        font-weight: 600;
        justify-content: center;
        white-space: nowrap;
        border: 1px solid var(--user-accent, #15803d) !important;
        background: #ffffff !important;
        color: var(--user-accent-dark, #166534) !important;
        transition: background .16s ease, border-color .16s ease, transform .16s ease;
        margin-inline: auto;
    }

    .action-btn:hover { background: var(--user-accent-soft, #ecfdf5) !important; border-color: var(--user-accent-dark, #166534) !important; color: var(--user-accent-dark, #166534) !important; transform: translateY(-1px); }

    .action-btn svg {
        width: 14px;
        height: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 32px 16px;
        color: #64748b;
    }

    html[data-theme="dark"] .variety-icon {
        background: #20382d;
        border-color: #365143;
        color: #b6d9aa;
    }

    html[data-theme="dark"] .rate-track {
        background: #35445a;
    }

    html[data-theme="dark"] .rate-fill {
        background: #8ab87b;
    }

    html[data-theme="dark"] .list-count {
        border-color: #35445a;
        color: #b9c9df;
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
        backdrop-filter: blur(4px);
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 680px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        background: linear-gradient(135deg, #ffffff 0%, #f1f8f2 100%);
        border: 1px solid #b9d2bd;
        border-radius: 22px;
        padding: 26px 28px 24px;
        box-shadow: 0 22px 54px rgba(24, 65, 35, 0.24);
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
        margin-bottom: 14px;
        padding-bottom: 14px;
        border-bottom: 1px solid #cbdccf;
    }

    .modal-title-custom {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 4px;
    }

    .modal-subtitle-custom {
        color: #64748b;
        font-size: 0.92rem;
        margin: 0;
    }

    .close-modal-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #b9d2bd;
        background: #f9fcf9;
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
        font-size: 0.84rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #cbdccf;
        background: #f4faf5;
        border-radius: 11px;
        min-height: 46px;
        padding: 10px 13px;
        color: #111827;
        font-size: .9rem;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .rice-type-name-input { text-transform: capitalize; }

    .rate-input-wrap {
        position: relative;
    }

    .rate-input-wrap .custom-input {
        padding-right: 38px;
    }

    .rate-input-suffix {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        color: #64748b;
        font-weight: 700;
        pointer-events: none;
    }

    .custom-textarea {
        min-height: 78px;
        resize: none;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .help-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 4px;
    }

    .preview-box {
        background: linear-gradient(135deg, #effcf2 0%, #e2f7e8 100%);
        border: 1px solid #38b866;
        border-radius: 11px;
        padding: 13px 15px;
        min-height: 76px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .preview-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
    }

    .preview-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #15803d;
        line-height: 1.1;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .btn-save {
        min-height: 44px;
        border-radius: 10px;
        padding: 9px 16px;
        background: #2f5d1e;
        border: none;
        color: #ffffff;
        font-size: .86rem;
        font-weight: 700;
    }

    .btn-save:hover {
        background: #274d19;
        color: #ffffff;
    }

    .btn-cancel {
        min-height: 44px;
        border-radius: 10px;
        padding: 9px 16px;
        background: #ffffff;
        border: 1px solid #d1d5db;
        color: #111827;
        font-size: .86rem;
        font-weight: 700;
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
@include('partials.form-dark-mode')

<div class="page-header">
    <div>
        <h1 class="page-title">Rice Types</h1>
        <p class="page-subtitle">Manage rice varieties and assigned recovery rates.</p>
    </div>

    <div class="page-actions">
        <a href="{{ route('owner.recovery-rate-history') }}" class="history-link">
            <i data-lucide="history"></i>
            History
        </a>
        <button type="button" class="btn btn-main d-flex align-items-center gap-2" onclick="openAddModal()">
            <i data-lucide="plus"></i>
            Add Rice Type
        </button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="summary-card green">
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
        <div class="summary-card green">
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

<div class="section-card rice-list-card">
    <div class="section-heading-row">
        <div class="section-heading-main">
            <span class="section-heading-icon"><i data-lucide="list-checks"></i></span>
            <div>
                <h2 class="section-title">Rice Type List</h2>
                <p class="section-subtitle">Manage varieties and the recovery rates used for delivery estimates.</p>
            </div>
        </div>
        <span class="list-count">{{ $totalRiceTypes }} {{ $totalRiceTypes === 1 ? 'variety' : 'varieties' }}</span>
    </div>

    <div class="table-responsive">
        <table class="table rice-table align-middle">
            <thead>
                <tr>
                    <th scope="col">Rice variety</th>
                    <th scope="col">Recovery rate</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="actions-cell">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riceTypes as $riceType)
                @php
                $rateWidth = min(max((float) $riceType->recovery_rate, 0), 100);
                @endphp
                <tr>
                    <td>
                        <div class="variety-cell">
                            <span class="rice-name">{{ $riceType->name }}</span>
                        </div>
                    </td>

                    <td>
                        <div class="rate-display">
                            <span class="rate-value">{{ number_format($riceType->recovery_rate, 2) }}%</span>
                            <div class="rate-track" aria-hidden="true">
                                <span class="rate-fill" data-rate="{{ $rateWidth }}"></span>
                            </div>
                        </div>
                    </td>

                    <td class="description-cell">{{ $riceType->description ?: 'No description added' }}</td>

                    <td>
                        <span class="status-badge {{ $riceType->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($riceType->status) }}
                        </span>
                    </td>

                    <td class="actions-cell">
                        <button
                            type="button"
                            class="btn btn-outline-success btn-sm action-btn"
                            aria-label="Edit {{ $riceType->name }}"
                            onclick="openEditModal(this)"
                            data-id="{{ $riceType->id }}"
                            data-name="{{ $riceType->name }}"
                            data-recovery-rate="{{ $riceType->recovery_rate }}"
                            data-status="{{ $riceType->status }}"
                            data-description="{{ $riceType->description }}">
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

            <button type="button" class="close-modal-btn" onclick="closeAddModal()" aria-label="Close">&times;</button>
        </div>

        <form method="POST" action="/owner/add-rice-type">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="field-label">Rice Type / Variety Name *</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control custom-input rice-type-name-input"
                        placeholder="Enter rice type name"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) *</label>
                    <div class="rate-input-wrap">
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="100"
                            id="addRecoveryRate"
                            name="recovery_rate"
                            class="form-control custom-input"
                            placeholder="Enter recovery rate"
                            required>
                        <span class="rate-input-suffix">%</span>
                    </div>
                    <div class="help-text">Used to estimate milled rice output.</div>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Status *</label>
                    <select name="status" class="form-select custom-select" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Typical Milling Output Preview</label>
                    <div class="preview-box">
                        <div class="preview-label">Estimated output from 100 kg palay</div>
                        <div id="addPreviewOutput" class="preview-value">0.00 kg</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control custom-textarea"
                        placeholder="Describe this rice type or variety"></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeAddModal()">
                    Cancel
                </button>

                <button type="submit" class="btn btn-save">
                    Save Rice Type
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

            <button type="button" class="close-modal-btn" onclick="closeEditModal()" aria-label="Close">&times;</button>
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
                        class="form-control custom-input rice-type-name-input"
                        placeholder="Enter rice type name"
                        required>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) *</label>
                    <div class="rate-input-wrap">
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="100"
                            id="editRecoveryRate"
                            name="recovery_rate"
                            class="form-control custom-input"
                            placeholder="Enter recovery rate"
                            required>
                        <span class="rate-input-suffix">%</span>
                    </div>
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
                        <div class="preview-label">Estimated output from 100 kg palay</div>
                        <div id="editPreviewOutput" class="preview-value">0.00 kg</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Description</label>
                    <textarea
                        id="editDescription"
                        name="description"
                        class="form-control custom-textarea"
                        placeholder="No description added"></textarea>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeEditModal()">
                    Cancel
                </button>

                <button type="submit" class="btn btn-save">
                    Save Changes
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

    function openEditModal(button) {
        const riceType = button.dataset;

        editForm.action = '/owner/edit-rice-type/' + riceType.id;

        document.getElementById('editName').value = riceType.name || '';
        document.getElementById('editRecoveryRate').value = riceType.recoveryRate || '';
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

    document.querySelectorAll('.rate-fill').forEach(function(element) {
        const rate = parseFloat(element.dataset.rate || 0);
        element.style.width = rate + '%';
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAddModal();
            closeEditModal();
        }
    });
</script>

@endsection
