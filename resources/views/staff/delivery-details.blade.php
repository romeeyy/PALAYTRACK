@extends('layouts.staff')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 3px;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #64748b;
        font-size: .95rem;
        margin: 0;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        border-radius: 11px;
        padding: 7px 12px;
        font-weight: 800;
    }

    .alert-custom {
        border: none;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 0.95rem;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
    }

    .section-card {
        background: linear-gradient(135deg, #f9fdf9 0%, #eaf5ec 100%);
        border: 1px solid #b9d2bd;
        border-radius: 16px;
        padding: 16px 18px;
        box-shadow: 0 14px 30px rgba(24, 65, 35, 0.10);
        margin-bottom: 14px;
    }

    .delivery-workspace {
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        background: linear-gradient(135deg, #eef6ea 0%, #f7fafc 55%, #eef6ea 100%);
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 11px;
    }

    .workflow-steps {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 4px 0 15px;
    }

    .workflow-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        min-width: 72px;
        color: #94a3b8;
        font-size: .76rem;
        font-weight: 800;
        text-align: center;
    }

    .workflow-step-marker {
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        background: #fff;
        color: #94a3b8;
        font-size: .78rem;
        font-weight: 900;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .workflow-step.is-current { color: var(--user-accent-dark, #166534); }
    .workflow-step.is-current .workflow-step-marker { border-color: var(--user-accent, #15803d); background: var(--user-accent-soft, #ecfdf5); color: var(--user-accent-dark, #166534); box-shadow: 0 0 0 4px var(--user-accent-ring, rgba(21,128,61,.12)); }
    .workflow-step.is-current .workflow-step-marker { animation: workflowPulse 2.4s ease-in-out infinite; }
    .workflow-step.is-complete { color: var(--user-accent-dark, #166534); }
    .workflow-step.is-complete .workflow-step-marker { border-color: var(--user-accent, #15803d); background: var(--user-accent, #15803d); color: #fff; }
    .workflow-connector { flex: 1; height: 2px; margin-top: 14px; background: #e2e8f0; }
    .workflow-connector.is-complete { background: var(--user-accent, #15803d); }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .info-item {
        background: transparent;
        border: 0;
        border-bottom: 1px solid #e5e7eb;
        border-radius: 0;
        padding: 11px 4px;
        min-height: 62px;
    }

    .delivery-info-card { padding: 14px 16px; }
    .delivery-info-card .section-subtitle { margin-bottom: 7px; }
    .delivery-info-card .info-grid { gap: 2px 16px; }
    .delivery-info-card .info-item { min-height: 52px; padding: 7px 4px; }
    .delivery-info-card .info-label { margin-bottom: 3px; font-size: .74rem; }
    .delivery-info-card .info-value { font-size: .88rem; }
    .delivery-info-card .status-info-item { display: flex; align-items: flex-start; }
    .delivery-info-card .status-inline { display: flex; align-items: center; justify-content: flex-start; width: 100%; gap: 8px; }
    .delivery-info-card .status-inline .info-label { margin-bottom: 0; }
    .delivery-info-card .status-inline .status-badge { padding: 5px 10px; font-size: .76rem; transform: translateY(4px); }

    .info-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: .92rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
    }

    .info-item.full {
        grid-column: 1 / -1;
        margin-top: 3px;
        padding: 9px 12px;
        border: 1px solid #cbdccf;
        border-radius: 12px;
        background: #f4faf5;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #a16207;
    }

    .status-processing {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-completed {
        background: #dcfce7;
        color: #15803d;
    }

    .status-claimed {
        background: #eef2f7;
        color: #475569;
    }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #cbdccf;
        background: #f4faf5;
        border-radius: 11px;
        min-height: 44px;
        padding: 10px 13px;
        color: #111827;
        box-shadow: none;
    }

    .milled-rice-form {
        padding: 13px;
        border: 1px solid #dbe7db;
        border-radius: 14px;
        background: var(--user-accent-soft, #f8fcf8);
    }

    .milled-rice-input-wrap { position: relative; }
    .milled-rice-input-wrap .custom-input { padding-right: 48px; background: #fff; }
    .milled-rice-unit { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: .82rem; font-weight: 800; pointer-events: none; }
    .milled-rice-help { margin: 6px 0 0; color: #64748b; font-size: .78rem; }

    .result-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .result-tile { display:flex; align-items:center; gap:12px; padding:13px 14px; border:1px solid #dbe3ec; border-radius:13px; background:#f8fafc; }
    .result-icon { width:36px; height:36px; flex:0 0 36px; display:grid; place-items:center; border-radius:10px; background:var(--user-accent-soft,#ecfdf5); color:var(--user-accent-dark,#166534); }
    .result-icon svg { width:18px; height:18px; }
    .result-label { margin:0 0 3px; color:#64748b; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.04em; }
    .result-value { margin:0; color:#0f172a; font-size:1.12rem; font-weight:900; }
    .result-value small { color:#64748b; font-size:.78rem; font-weight:700; }
    .result-tile.result-helper-success { background:#f0fdf4; border-color:#86efac; }
    .result-tile.result-helper-success .result-icon { background:#dcfce7; color:#15803d; }
    .result-tile.result-helper-warning { background:#fffaf2; border-color:#fdba74; }
    .result-tile.result-helper-warning .result-icon { background:#ffedd5; color:#c2410c; }
    @media (max-width:576px) { .result-grid { grid-template-columns:1fr; } }

    .custom-textarea {
        min-height: 58px;
        resize: vertical;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .custom-input.is-invalid,
    .custom-select.is-invalid,
    .custom-textarea.is-invalid,
    .custom-input.is-invalid:focus,
    .custom-select.is-invalid:focus,
    .custom-textarea.is-invalid:focus {
        border-color: #ef4444;
        background-color: #fffafa;
        box-shadow: 0 0 0 0.16rem rgba(239, 68, 68, 0.08);
    }

    .invalid-feedback {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        color: #b42318;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .invalid-feedback::before {
        content: "!";
        display: inline-grid;
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        place-items: center;
        border-radius: 50%;
        background: #fee4e2;
        color: #b42318;
        font-size: 0.68rem;
        font-weight: 800;
    }

    .field-label {
        font-size: 0.92rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        display: block;
    }

    .action-btn {
        min-height: 42px;
        border-radius: 12px;
        padding: 8px 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(15, 23, 42, .12); }

    .action-btn svg { width: 18px; height: 18px; }

    .workflow-hint { display:flex; align-items:center; gap:7px; color:#64748b; margin-bottom:8px; }
    .workflow-hint svg { width:16px; height:16px; color:#f59e0b; }
    .section-card .btn-primary { background:var(--user-accent, #2563eb); border-color:var(--user-accent, #2563eb); }
    .section-card .btn-primary:hover { background:var(--user-accent-dark, #1d4ed8); border-color:var(--user-accent-dark, #1d4ed8); }

    .helper-box {
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 0.94rem;
        margin-bottom: 16px;
    }

    .helper-warning {
        background: #fff7ed;
        color: #9a3412;
        border: 1px solid #fdba74;
        padding: 10px 12px;
        font-size: .86rem;
    }

    .helper-dark {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .helper-success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #86efac;
    }

    .table-soft {
        margin-bottom: 0;
    }

    .table-soft thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #334155;
        font-weight: 700;
        white-space: nowrap;
        padding: 14px 12px;
        font-size: 0.92rem;
    }

    .table-soft tbody td {
        border-bottom: 1px solid #eef2f7;
        padding: 14px 12px;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #334155;
    }

    .table-soft tbody tr:last-child td {
        border-bottom: none;
    }

    .empty-state {
        text-align: center;
        color: #64748b;
        padding: 22px 12px;
        font-size: 0.95rem;
    }

    .btn-main {
        background: linear-gradient(135deg, #2f5d1e 0%, #3f7a28 100%);
        color: #fff;
        border: none;
        box-shadow: 0 8px 16px rgba(47, 93, 30, 0.16);
    }

    .btn-main:hover {
        background: linear-gradient(135deg, #274d19 0%, #35671f 100%);
        color: #fff;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .action-card:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(15, 23, 42, .10); }

    @keyframes workflowPulse {
        0%, 100% { box-shadow: 0 0 0 4px var(--user-accent-ring, rgba(21,128,61,.12)); }
        50% { box-shadow: 0 0 0 7px color-mix(in srgb, var(--user-accent, #15803d) 10%, transparent); }
    }

    .action-card.equal-height {
        height: 100%;
        min-height: 180px;
    }

    .delivery-actions-grid .action-card:nth-child(1) { border-top: 3px solid var(--user-accent, #15803d); }
    .delivery-actions-grid .action-card:nth-child(2) { border-top: 3px solid var(--user-accent, #15803d); }
    .delivery-actions-grid .action-card:nth-child(3) { border-top: 3px solid #f59e0b; }

    .action-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 5px;
        line-height: 1.3;
    }

    .action-card-subtitle {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 0;
    }

    .action-card-body {
        display: flex;
        flex-direction: column;
        gap: 12px;
        height: 100%;
    }

    .action-card-top {
        margin-bottom: 4px;
    }

    .action-card-title-row { display:flex; align-items:center; gap:9px; }
    .action-card-icon { width:30px; height:30px; display:grid; place-items:center; flex:0 0 30px; border-radius:9px; background:var(--user-accent-soft,#ecfdf5); color:var(--user-accent-dark,#166534); }
    .action-card-icon svg { width:16px; height:16px; }
    .delivery-actions-grid .action-card:nth-child(2) .action-card-icon { background:var(--user-accent-soft,#ecfdf5); color:var(--user-accent-dark,#166534); }
    .delivery-actions-grid .action-card:nth-child(3) .action-card-icon { background:#fff7ed; color:#c2410c; }

    .action-btn-lg {
        min-height: 46px;
        border-radius: 11px;
        font-size: .9rem;
        font-weight: 800;
        padding: 10px 15px;
    }

    .action-btn-lg svg { width:18px; height:18px; }

    .btn-dark-soft {
        background: var(--user-accent, #15803d);
        color: #fff;
        border: none;
        box-shadow: 0 8px 16px var(--user-accent-ring, rgba(21, 128, 61, .18));
    }

    .btn-dark-soft:hover {
        color: #fff;
        background: var(--user-accent-dark, #166534);
    }

    .payment-status-box {
        background: #f8fafc;
        border: 1px solid #dbe3ee;
        border-radius: 11px;
        padding: 11px 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .payment-status-label {
        font-size: 1rem;
        font-weight: 700;
        color: #334155;
        margin: 0;
    }

    .payment-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        background: #198754;
        color: #fff;
        font-size: 0.84rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .claim-highlight {
        background: linear-gradient(135deg, #f8fafc 0%, #eef6ee 100%);
        border: 1px solid #dbe7db;
        border-radius: 12px;
        padding: 13px 15px;
    }

    .claim-highlight-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .claim-highlight-text {
        font-size: 0.94rem;
        color: #64748b;
        margin-bottom: 0;
    }

    .sms-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        min-height: 32px;
        white-space: nowrap;
    }

    .sms-sent,
    .sms-reached {
        background: #dcfce7;
        color: #15803d;
    }

    .sms-failed {
        background: #fee2e2;
        color: #b91c1c;
    }

    .sms-none {
        background: #e5e7eb;
        color: #475569;
    }

    .failed-notification-box {
    background: #ffffff;
    border: 1px solid #fee2e2;
    border-left: 6px solid #ef4444;
    border-radius: 18px;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    box-shadow: 0 10px 22px rgba(239, 68, 68, 0.08);
    flex-wrap: wrap;
}

.failed-notification-left {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.failed-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #fee2e2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ef4444;
    font-weight: bold;
    font-size: 18px;
}

.failed-text-group {
    display: flex;
    flex-direction: column;
}

.failed-notification-text {
    color: #b91c1c;
    font-weight: 800;
    font-size: 1rem;
}

.failed-notification-subtext {
    color: #64748b;
    font-size: 0.9rem;
}

.btn-resend-sms {
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 800;
    font-size: 0.9rem;
    box-shadow: 0 8px 16px rgba(245, 158, 11, 0.25);
    transition: all 0.2s ease;
}

    .btn-resend-sms:hover {
    transform: translateY(-1px);
    background: linear-gradient(135deg, #d97706, #ea580c);
}

@media (max-width: 992px) {
    .info-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .workflow-step { min-width: 62px; }
}

.compact-section .helper-box {
    margin-bottom: 0;
    padding: 11px 13px;
}

.notification-card .helper-box {
    margin-bottom: 10px;
    padding: 10px 12px;
}

.notification-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 10px;
}

.notification-title-group { display:flex; align-items:center; gap:10px; }
.notification-title-icon { width:34px; height:34px; display:grid; place-items:center; border-radius:10px; background:var(--user-accent-soft,#ecfdf5); color:var(--user-accent-dark,#166534); }
.notification-title-icon svg { width:17px; height:17px; }

.notification-heading .section-title {
    margin: 0;
}

.notification-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #64748b;
    font-size: .8rem;
    font-weight: 700;
}

.notification-status .sms-status-badge { min-height: 28px; padding: 6px 10px; font-size: .78rem; }

.notification-notice {
    margin: 0 0 12px;
    padding: 8px 10px;
    border-left: 3px solid #f59e0b;
    border-radius: 7px;
    background: #fffaf2;
    color: #9a3412;
    font-size: .8rem;
}

.notification-card .section-subtitle {
    margin-bottom: 10px;
}

.notification-fields {
    display: grid;
    grid-template-columns: .9fr .9fr 1fr 1.35fr auto;
    gap: 10px;
    align-items: end;
}

@media (min-width: 1101px) {
    .notification-fields { grid-template-columns: minmax(0, .9fr) minmax(0, .9fr) minmax(180px, 1.2fr); }
    .notification-fields > div:nth-child(4) { grid-column: 1 / 3; }
    .notification-fields > div:nth-child(5) { grid-column: 3; }
}

.notification-action {
    min-width: 150px;
}

.notification-action .action-btn {
    min-height: 44px;
    border: 0;
    background: var(--user-accent, #15803d);
    color: #fff;
    border-radius: 11px;
    font-weight: 800;
    box-shadow: 0 7px 15px var(--user-accent-ring, rgba(21,128,61,.18));
}

.notification-action .action-btn:hover {
    background: var(--user-accent-dark, #166534);
    color: #fff;
}

.notification-card .custom-textarea {
    height: 44px;
    min-height: 44px;
    resize: none;
    overflow: hidden;
}

.notification-history-card .empty-state {
    padding: 14px 10px;
}

    .history-method { display:inline-flex; align-items:center; padding:5px 9px; border-radius:999px; background:#eff6ff; color:#1d4ed8; font-size:.76rem; font-weight:800; }
    .notification-history-card .table-soft tbody tr:hover { background:#f8fafc; }
    .notification-history-title { display:flex; align-items:center; gap:10px; }
    .notification-history-title .notification-title-icon { width:32px; height:32px; }

.notification-history-card .section-subtitle {
    margin-bottom: 8px;
}

.claim-action-card .claim-highlight {
    height: 100%;
}

.delivery-actions-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

@media (max-width: 1100px) {
    .delivery-actions-grid { grid-template-columns: 1fr; }
    .notification-fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .notification-action { min-width: 0; }
}

/* mobile fix */
@media (max-width: 576px) {
    .info-grid { grid-template-columns: 1fr; }
    .workflow-steps { gap: 4px; }
    .workflow-step { min-width: 0; flex: 1; font-size: .68rem; }
    .workflow-step-marker { width: 27px; height: 27px; }
    .page-title { font-size: 1.7rem; }
    .section-card { padding: 18px; }
    .notification-fields { grid-template-columns: 1fr; }
    .notification-heading { align-items:flex-start; flex-direction:column; }

    .failed-notification-box {
        flex-direction: column;
        align-items: flex-start;
    }

    .btn-resend-sms {
        width: 100%;
        text-align: center;
    }
}
</style>

@php
    $statusClass = match($delivery->status) {
        'pending' => 'status-pending',
        'processing' => 'status-processing',
        'completed' => 'status-completed',
        'claimed' => 'status-claimed',
        default => 'status-claimed',
    };

    $canEditActualRice = $delivery->status === 'processing';
    $workflowStatuses = ['pending', 'processing', 'completed', 'claimed'];
    $workflowIndex = array_search($delivery->status, $workflowStatuses, true);
    $workflowIndex = $workflowIndex === false ? 0 : $workflowIndex;

    $variance = null;
    $varianceLabel = null;
    $varianceClass = 'helper-dark';

    if ($delivery->actual_rice !== null) {
        $variance = $delivery->actual_rice - $delivery->estimated_rice;

        if ($variance > 0) {
            $varianceLabel = 'Above estimate';
            $varianceClass = 'helper-success';
        } elseif ($variance < 0) {
            $varianceLabel = 'Below estimate';
            $varianceClass = 'helper-warning';
        } else {
            $varianceLabel = 'Matched estimate';
            $varianceClass = 'helper-dark';
        }
    }

    $latestNotification = $delivery->notifications->sortByDesc('created_at')->first();
    $smsStatus = $latestNotification?->notification_status ?? null;
    $isNotified = $delivery->hasSuccessfulNotification();
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Delivery Details</h1>
        <p class="page-subtitle">
            View and process the complete delivery record.
        </p>
    </div>

    <a href="{{ route('staff.deliveries', ['view' => request('view', 'active')]) }}" class="btn btn-outline-secondary back-btn">
        <i data-lucide="arrow-left"></i>
        Back to Deliveries
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-custom mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-custom mb-4">
        {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-custom mb-4">
        {{ session('warning') }}
    </div>
@endif

<div class="delivery-workspace">
<div class="section-card compact-section delivery-info-card">
    <h2 class="section-title">Delivery Information</h2>
    <p class="section-subtitle">Basic delivery, client, and milling information.</p>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Delivery ID</div>
            <div class="info-value">{{ $delivery->delivery_id }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Daily Queue Number</div>
            <div class="info-value">#{{ $delivery->queue_number }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Delivery Date</div>
            <div class="info-value">
                {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('m/d/Y') : 'N/A' }}
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">Client Name</div>
            <div class="info-value">{{ $delivery->client_name }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Contact Number</div>
            <div class="info-value">{{ $delivery->contact_number }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Recorded By</div>
            <div class="info-value">{{ $delivery->staff?->name ?? 'N/A' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Rice Type</div>
            <div class="info-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
        </div>

        <div class="info-item status-info-item">
            <div class="status-inline">
                <div class="info-label">Status</div>
                <span class="status-badge {{ $statusClass }}">
                    {{ ucfirst($delivery->status) }}
                </span>
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">Sacks</div>
            <div class="info-value">{{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Palay Weight</div>
            <div class="info-value">{{ number_format($delivery->palay_weight, 2) }} kg</div>
        </div>

        <div class="info-item">
            <div class="info-label">Recovery Rate</div>
            <div class="info-value">{{ number_format($delivery->recovery_rate, 2) }}%</div>
        </div>

        <div class="info-item">
            <div class="info-label">Estimated Milled Rice</div>
            <div class="info-value">{{ number_format($delivery->estimated_rice, 2) }} kg</div>
        </div>

        <div class="info-item">
            <div class="info-label">Actual Milled Rice</div>
            <div class="info-value">
                {{ $delivery->actual_rice !== null ? number_format($delivery->actual_rice, 2) . ' kg' : 'Not yet recorded' }}
            </div>
        </div>

        @if($variance !== null)
            <div class="info-item">
                <div class="info-label">Variance</div>
                <div class="info-value">
                    {{ $variance > 0 ? '+' : '' }}{{ number_format($variance, 2) }} kg
                    ({{ $varianceLabel }})
                </div>
            </div>
        @endif

        <div class="info-item full">
            <div class="info-label">Notes</div>
            <div class="info-value">{{ $delivery->notes ?: 'No notes provided.' }}</div>
        </div>
    </div>
</div>

<div class="section-card compact-section">
    <h2 class="section-title">Workflow Status</h2>

    <div class="workflow-steps" aria-label="Delivery workflow">
        @foreach($workflowStatuses as $stepIndex => $workflowStatus)
            @php
                $stepIsComplete = $stepIndex < $workflowIndex
                    || ($delivery->status === 'completed' && $stepIndex === $workflowIndex)
                    || ($delivery->status === 'claimed' && $stepIndex === $workflowIndex);
            @endphp
            <div class="workflow-step {{ $stepIsComplete ? 'is-complete' : '' }} {{ $stepIndex === $workflowIndex ? 'is-current' : '' }}">
                <span class="workflow-step-marker">{{ $stepIsComplete ? '✓' : $stepIndex + 1 }}</span>
                <span class="workflow-step-label">{{ ucfirst($workflowStatus) }}</span>
            </div>
            @if($stepIndex < count($workflowStatuses) - 1)
                <span class="workflow-connector {{ $stepIndex < $workflowIndex ? 'is-complete' : '' }}"></span>
            @endif
        @endforeach
    </div>

    @if($delivery->status === 'pending')
        <p class="section-subtitle workflow-hint"><i data-lucide="clock-3"></i>Start milling when this delivery reaches the front of the active queue.</p>
        <form method="POST" action="{{ url('/staff/delivery-status/' . $delivery->id) }}"
              data-confirm-title="Start Processing?"
              data-confirm-message="Move this delivery from Pending to Processing?"
              data-confirm-button="Start Processing">
            @csrf
            <input type="hidden" name="status" value="processing">
            <button type="submit" class="btn btn-primary action-btn">
                <i data-lucide="play-circle"></i>
                Start Processing
            </button>
        </form>
    @elseif($delivery->status === 'processing')
        <div class="helper-box helper-dark mb-0">
            Milling is in progress. Enter the actual milled rice below when processing is finished.
        </div>
    @elseif($delivery->status === 'completed')
        <div class="helper-box helper-dark mb-0">
            Milling is complete. Continue to payment, then release the rice to the customer.
        </div>
    @else
        <div class="helper-box helper-dark mb-0">
            This delivery is finalized and retained as a read-only historical record.
        </div>
    @endif
</div>

@if($canEditActualRice)
    <div class="section-card compact-section">
        <h2 class="section-title">Update Actual Milled Rice</h2>
        <p class="section-subtitle">Record the actual milled rice output for inventory tracking.</p>

        <form method="POST" action="{{ url('/staff/actual-rice/' . $delivery->id) }}"
              data-confirm-title="Complete Milling?"
              data-confirm-message="Save the actual milled rice and mark this delivery as Completed? Inventory will be updated."
              data-confirm-button="Save & Complete">
            @csrf
            <input type="hidden" name="completion_token" value="{{ $completionToken }}">

            <div class="milled-rice-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="field-label">Actual Milled Rice (kg)</label>
                    <div class="milled-rice-input-wrap">
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="{{ $delivery->palay_weight }}"
                            name="actual_rice"
                            class="form-control custom-input @error('actual_rice') is-invalid @enderror"
                            placeholder="Enter actual output"
                            value="{{ old('actual_rice', $delivery->actual_rice) }}"
                            required
                        >
                        <span class="milled-rice-unit">kg</span>
                    </div>
                    @error('actual_rice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-success action-btn w-100">
                        <i data-lucide="check-circle-2"></i>
                        Save & Complete Milling
                    </button>
                </div>
            </div>
            </div>
        </form>

        @if($delivery->actual_rice !== null)
            <div class="helper-box helper-dark mt-3 mb-0">
                <strong>Current Saved Value:</strong> {{ number_format($delivery->actual_rice, 2) }} kg
            </div>
        @endif
    </div>
@else
    <div class="section-card compact-section">
        <h2 class="section-title">Actual Milled Rice Record</h2>
        <p class="section-subtitle">Recorded actual milled rice output for this delivery.</p>

        @if($delivery->actual_rice !== null)
            <div class="result-grid">
                <div class="result-tile">
                    <span class="result-icon"><i data-lucide="scale"></i></span>
                    <div>
                        <p class="result-label">Actual Milled Rice</p>
                        <p class="result-value">{{ number_format($delivery->actual_rice, 2) }} <small>kg</small></p>
                    </div>
                </div>
                <div class="result-tile result-{{ $varianceClass }}">
                    <span class="result-icon"><i data-lucide="{{ $variance < 0 ? 'trending-down' : 'trending-up' }}"></i></span>
                    <div>
                        <p class="result-label">Variance</p>
                        <p class="result-value">{{ $variance > 0 ? '+' : '' }}{{ number_format($variance, 2) }} <small>kg · {{ $varianceLabel }}</small></p>
                    </div>
                </div>
            </div>
        @else
            <div class="helper-box helper-warning mb-0">
                Actual milled rice can only be entered when the delivery status is <strong>Processing</strong>.
            </div>
        @endif
    </div>
@endif

@php
    $smsEnabled = (string) \App\Models\Setting::getValue('sms_enabled', '0');
@endphp

@if($delivery->status === 'completed' && $smsEnabled !== '1' && !$isNotified)
<div class="section-card notification-card">
    <div class="notification-heading">
        <div class="notification-title-group">
            <span class="notification-title-icon"><i data-lucide="bell-ring"></i></span>
            <h2 class="section-title">Log Farmer Notification</h2>
        </div>
        <div class="notification-status">
            <span>Latest status</span>
        @if($smsStatus)
            <span class="sms-status-badge
                {{ $smsStatus === 'sent' ? 'sms-sent' : '' }}
                {{ $smsStatus === 'reached' ? 'sms-reached' : '' }}
                {{ $smsStatus === 'failed' ? 'sms-failed' : '' }}
            ">
                {{ ucfirst($smsStatus) }}
            </span>
        @else
            <span class="sms-status-badge sms-none">No SMS yet</span>
        @endif
        </div>
    </div>

    <p class="notification-notice">Automatic SMS is disabled. Notify the farmer manually, then record every attempt below.</p>

    <form method="POST" action="{{ url('/staff/delivery-notification/' . $delivery->id) }}" class="notification-form">
        @csrf

        <div class="notification-fields">
            <div>
                <label class="field-label">Method</label>
                <select name="method" class="form-select custom-select @error('method') is-invalid @enderror" required>
                    <option value="">Select Method</option>
                    <option value="call" {{ old('method') == 'call' ? 'selected' : '' }}>Call</option>
                    <option value="text" {{ old('method') == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="in_person" {{ old('method') == 'in_person' ? 'selected' : '' }}>In Person</option>
                </select>
                @error('method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="field-label">Notification Status</label>
                <select name="notification_status" class="form-select custom-select @error('notification_status') is-invalid @enderror" required>
                    <option value="">Select Status</option>
                    <option value="sent" {{ old('notification_status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="reached" {{ old('notification_status') == 'reached' ? 'selected' : '' }}>Reached</option>
                    <option value="failed" {{ old('notification_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('notification_status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="field-label">Notified At</label>
                <input
                    type="datetime-local"
                    name="notified_at"
                    class="form-control custom-input @error('notified_at') is-invalid @enderror"
                    value="{{ old('notified_at') }}"
                    required
                >
                @error('notified_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="field-label">Remarks</label>
                <textarea
                    name="remarks"
                    class="form-control custom-textarea @error('remarks') is-invalid @enderror"
                    rows="1"
                    placeholder="Optional remarks..."
                >{{ old('remarks') }}</textarea>
                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="notification-action">
                <button type="submit" class="btn action-btn w-100">
                    <i data-lucide="send"></i>
                    Save Notification
                </button>
            </div>
        </div>
    </form>
</div>
@endif

@if($delivery->actual_rice !== null)
<div class="section-card notification-history-card">
    <div class="notification-history-title">
        <span class="notification-title-icon"><i data-lucide="history"></i></span>
        <h2 class="section-title">Notification History</h2>
    </div>
    <p class="section-subtitle">Previous notification logs for this delivery.</p>

    @if($latestNotification)
        @if($latestNotification->notification_status === 'failed'
            && $latestNotification->method === 'text'
            && $latestNotification->source !== 'manual'
            && $smsEnabled === '1')
           <div class="failed-notification-box">

    <div class="failed-notification-left">
        
        <div class="failed-icon">
            !
        </div>

        <div class="failed-text-group">
            <span class="failed-notification-text">
                Message Failed
            </span>
            <span class="failed-notification-subtext">
                The SMS was not delivered. You can resend it.
            </span>
        </div>

    </div>

    <form method="POST"
          action="{{ route('staff.resend-sms', $latestNotification->id) }}"
          data-confirm-title="Resend SMS?"
          data-confirm-message="The previous SMS failed. Do you want to resend the notification?"
          data-confirm-button="Resend SMS">
        @csrf

        <button type="submit" class="btn-resend-sms">
            Resend SMS
        </button>
    </form>

</div>
        @endif

        <div class="table-responsive">
            <table class="table table-soft align-middle">
                <thead>
                    <tr>
                        <th>Attempted At</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($delivery->notifications->sortByDesc('created_at') as $notification)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($notification->notified_at ?? $notification->created_at)->format('M d · g:i A') }}
                            </td>
                            <td><span class="history-method">{{ ucwords(str_replace('_', ' ', $notification->method)) }}</span></td>
                            <td>
                                <span class="sms-status-badge
                                    {{ $notification->notification_status === 'sent' ? 'sms-sent' : '' }}
                                    {{ $notification->notification_status === 'reached' ? 'sms-reached' : '' }}
                                    {{ $notification->notification_status === 'failed' ? 'sms-failed' : '' }}
                                ">
                                    {{ ucfirst($notification->notification_status) }}
                                </span>
                            </td>
                            <td>{{ $notification->remarks ?: 'No remarks' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            No notification logs yet.
        </div>
    @endif
</div>
@endif

<div class="delivery-actions-grid">
    <div class="action-card equal-height">
        <div class="action-card-body">
            <div class="action-card-top">
                <div class="action-card-title-row"><span class="action-card-icon"><i data-lucide="file-text"></i></span><h2 class="action-card-title">Claim Stub</h2></div>
                <p class="action-card-subtitle">Open or reprint the customer’s claim ticket.</p>
            </div>
            <a href="{{ url('/staff/claim-stub/' . $delivery->id) }}"
               class="btn btn-main action-btn-lg w-100 d-flex align-items-center justify-content-center gap-2 mt-auto">
                <i data-lucide="file-text"></i>
                Open Claim Stub
            </a>
        </div>
    </div>

    <div class="action-card equal-height">
        <div class="action-card-body">
            <div class="action-card-top">
                <div class="action-card-title-row"><span class="action-card-icon"><i data-lucide="wallet-cards"></i></span><h2 class="action-card-title">Billing &amp; Payment</h2></div>
                <p class="action-card-subtitle">Record payment or open the completed receipt.</p>
            </div>
            @if($delivery->transaction)
                <div class="d-flex flex-column gap-2 mt-auto">
                    <div class="payment-status-box">
                        <p class="payment-status-label mb-0">Payment</p>
                        <span class="payment-pill">Paid</span>
                    </div>
                    <a href="{{ url('/staff/receipt/' . $delivery->id) }}"
                       class="btn btn-main action-btn-lg w-100 d-flex justify-content-center align-items-center gap-2">
                        <i data-lucide="file-text"></i>
                        View Receipt
                    </a>
                </div>
            @elseif($delivery->status === 'completed' && $isNotified)
                <a href="{{ url('/staff/pos/' . $delivery->id) }}"
                   class="btn btn-success action-btn-lg w-100 mt-auto d-flex align-items-center justify-content-center gap-2">
                    <i data-lucide="credit-card"></i>
                    Proceed to Payment
                </a>
            @elseif($delivery->status === 'completed')
                <div class="helper-box helper-warning mb-0 mt-auto">Notify the farmer successfully before payment.</div>
            @else
                <div class="helper-box helper-warning mb-0 mt-auto">Complete milling before payment.</div>
            @endif
        </div>
    </div>

    <div class="action-card equal-height">
        <div class="action-card-body">
            <div class="action-card-top">
                <div class="action-card-title-row"><span class="action-card-icon"><i data-lucide="package-check"></i></span><h2 class="action-card-title">Release Delivery</h2></div>
                <p class="action-card-subtitle">Mark the rice as claimed after payment and release.</p>
            </div>
            @if($delivery->status === 'claimed')
                <div class="helper-box helper-dark mb-0 mt-auto">This delivery has already been claimed.</div>
            @elseif($delivery->status === 'completed' && $isNotified && $delivery->transaction?->payment_status === 'paid')
                <form method="POST" action="{{ url('/staff/claim-delivery/' . $delivery->id) }}"
                      class="mt-auto"
                      data-confirm-title="Mark as Claimed?"
                      data-confirm-message="Confirm that the milled rice has been released to the customer."
                      data-confirm-button="Mark as Claimed">
                    @csrf
                    <button type="submit" class="btn btn-dark-soft action-btn-lg w-100 d-flex align-items-center justify-content-center gap-2">
                        <i data-lucide="package-check"></i>
                        Mark as Claimed
                    </button>
                </form>
            @elseif($delivery->status === 'completed' && !$isNotified)
                <div class="helper-box helper-warning mb-0 mt-auto">Notify the farmer successfully before releasing this delivery.</div>
            @elseif($delivery->status === 'completed')
                <div class="helper-box helper-warning mb-0 mt-auto">Complete payment before releasing this delivery.</div>
            @else
                <div class="helper-box helper-warning mb-0 mt-auto">Complete milling before releasing this delivery.</div>
            @endif
        </div>
    </div>
</div>
</div>

<script>
    // Close the native date/time picker after a date is selected (including Today).
    document.querySelectorAll('input[type="datetime-local"]').forEach(function (input) {
        input.addEventListener('change', function () {
            window.requestAnimationFrame(function () { input.blur(); });
        });
    });
</script>

@include('partials.field-validation-focus')
@endsection
