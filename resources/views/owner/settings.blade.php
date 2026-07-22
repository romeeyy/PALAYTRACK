@extends('layouts.owner')

@section('content')

<style>
    .page-header {
        margin-bottom: 22px;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 5px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    .settings-grid {
        display: grid;
        gap: 18px;
    }

    .settings-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 22px 24px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
    }

    .settings-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .settings-title {
        font-size: 1.15rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 5px;
    }

    .settings-subtitle {
        color: #64748b;
        font-size: 0.92rem;
        margin: 0;
        line-height: 1.5;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 13px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.82rem;
    }

    .status-enabled {
        background: #dcfce7;
        color: #15803d;
    }

    .status-disabled {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .settings-body {
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 18px;
        flex-wrap: wrap;
    }

    .field-label {
        font-weight: 800;
        color: #111827;
        font-size: 0.9rem;
        margin-bottom: 7px;
        display: block;
    }

    .custom-input {
        min-height: 46px;
        border-radius: 11px;
        border: 1px solid #d1d5db;
        padding: 10px 13px;
        font-size: 0.92rem;
        box-shadow: none;
    }

    .custom-input:focus {
        border-color: #2f5d1e;
        box-shadow: 0 0 0 3px rgba(47, 93, 30, 0.10);
    }

    .custom-toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-toggle-input {
        display: none;
    }

    .custom-toggle-label {
        width: 56px;
        height: 30px;
        background: #e5e7eb;
        border-radius: 999px;
        position: relative;
        cursor: pointer;
        transition: 0.2s;
    }

    .custom-toggle-label::after {
        content: "";
        width: 24px;
        height: 24px;
        background: #fff;
        position: absolute;
        top: 3px;
        left: 3px;
        border-radius: 50%;
        transition: 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.18);
    }

    .custom-toggle-input:checked + .custom-toggle-label {
        background: #15803d;
    }

    .custom-toggle-input:checked + .custom-toggle-label::after {
        left: 29px;
    }

    .toggle-text {
        font-weight: 800;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .btn-main {
        background: #2f5d1e;
        color: #fff;
        padding: 11px 18px;
        border-radius: 11px;
        font-weight: 800;
        border: none;
        min-width: 160px;
    }

    .btn-main:hover {
        background: #274d19;
        color: #fff;
    }

    .btn-main:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    .btn-history {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 11px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #334155;
        font-weight: 800;
        font-size: 0.86rem;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-history:hover {
        background: #f8fafc;
        border-color: #2f5d1e;
        color: #2f5d1e;
        text-decoration: none;
    }

    .alert-success-custom,
    .alert-error-custom {
        padding: 12px 14px;
        border-radius: 12px;
        margin-bottom: 16px;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        color: #065f46;
    }

    .alert-error-custom {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .history-table-wrap {
        border: 1px solid #e5e7eb;
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
    }

    .history-table-scroll {
        max-height: 320px;
        overflow-y: auto;
    }

    .history-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .history-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .history-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
        vertical-align: middle;
        white-space: nowrap;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-table tbody tr:hover {
        background: #f9fafb;
    }

    .type-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.78rem;
        font-weight: 900;
    }

    .fee-old {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
        font-weight: 900;
    }

    .fee-new {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 10px;
        background: #ecfdf5;
        color: #047857;
        font-weight: 900;
    }

    .changed-by {
        font-weight: 800;
        color: #0f172a;
    }

    .date-muted {
        color: #64748b;
        font-weight: 700;
        font-size: 0.84rem;
    }

    .empty-history {
        text-align: center;
        padding: 34px 16px;
        color: #64748b;
        font-weight: 700;
    }

    .history-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .history-note {
        color: #64748b;
        font-size: 0.84rem;
        font-weight: 700;
        margin: 0;
    }

    .toast-message {
        position: fixed;
        top: 24px;
        right: 24px;
        width: 330px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-left: 5px solid #15803d;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-12px);
        transition: 0.25s ease;
        z-index: 9999;
    }

    .toast-message.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .toast-icon {
        width: 30px;
        height: 30px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        flex-shrink: 0;
    }

    .toast-title {
        font-size: 0.92rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .toast-text {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
    }

    @media (max-width: 768px) {
        .settings-card {
            padding: 18px;
        }

        .settings-body {
            align-items: stretch;
        }

        .btn-main {
            width: 100%;
        }

        .toast-message {
            left: 16px;
            right: 16px;
            top: 18px;
            width: auto;
        }

        .history-footer {
            align-items: stretch;
        }

        .btn-history {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Manage system configuration and operational preferences.</p>
</div>

@if(session('success'))
    <div class="alert-success-custom">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-error-custom">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="settings-grid">
    <div class="settings-card">
        <div class="settings-card-header">
            <div>
                <h2 class="settings-title">SMS Notifications</h2>
                <p class="settings-subtitle">Enable or disable automatic SMS alerts when a delivery is completed.</p>
            </div>

            <span id="smsStatusBadge" class="status-badge {{ $smsEnabled === '1' ? 'status-enabled' : 'status-disabled' }}">
                <span class="status-dot"></span>
                <span id="smsStatusText">{{ $smsEnabled === '1' ? 'Enabled' : 'Disabled' }}</span>
            </span>
        </div>

        <form method="POST" action="{{ route('owner.sms-settings') }}"
            data-confirm-title="Update SMS Setting?"
            data-confirm-message="Save this SMS notification preference?"
            data-confirm-button="Save Changes">
            @csrf

            <input type="hidden" name="sms_enabled" id="smsHidden" value="{{ $smsEnabled }}">

            <div class="settings-body">
                <div>
                    <label class="field-label">Notification Toggle</label>

                    <div class="custom-toggle-wrap">
                        <input
                            type="checkbox"
                            id="sms_enabled"
                            class="custom-toggle-input"
                            {{ $smsEnabled === '1' ? 'checked' : '' }}
                            onchange="updateSmsToggle(this)"
                        >

                        <label for="sms_enabled" class="custom-toggle-label"></label>

                        <span id="toggleText" class="toggle-text">
                            {{ $smsEnabled === '1' ? 'Turned On' : 'Turned Off' }}
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-main">
                    Save Changes
                </button>
            </div>
            @if($latestSmsHistory)
                <p class="history-note mt-3">Last changed {{ $latestSmsHistory->changed_at->format('M d, Y h:i A') }} by {{ $latestSmsHistory->user->name ?? 'Owner' }}.</p>
            @endif
        </form>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <div>
                <h2 class="settings-title">Milling Fee Settings</h2>
                <p class="settings-subtitle">Set the default milling fee based on milling type.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.milling-fee-settings') }}"
            data-confirm-title="Update Milling Fees?"
            data-confirm-message="Save these rates? New fees will apply to future deliveries only."
            data-confirm-button="Update Milling Fees">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="field-label">Menudo Fee (₱ / kg)</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="100"
                        name="menudo_fee"
                        class="form-control custom-input"
                        value="{{ old('menudo_fee', $menudoFee) }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="field-label">Commercial Fee (₱ / kg)</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="100"
                        name="commercial_fee"
                        class="form-control custom-input"
                        value="{{ old('commercial_fee', $commercialFee) }}"
                        required
                    >
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-main">
                    Update Milling Fees
                </button>
            </div>
        </form>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <div>
                <h2 class="settings-title">Milling Fee Change History</h2>
                <p class="settings-subtitle">Latest milling fee changes with previous and updated values.</p>
            </div>
        </div>

        <div class="history-table-wrap">
            <div class="history-table-scroll">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Milling Type</th>
                            <th>Old Fee</th>
                            <th>New Fee</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeHistories as $history)
                            <tr>
                                <td>
                                    <span class="type-pill">
                                        {{ ucfirst($history->milling_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fee-old">
                                        ₱{{ number_format($history->old_fee, 2) }}/kg
                                    </span>
                                </td>
                                <td>
                                    <span class="fee-new">
                                        ₱{{ number_format($history->new_fee, 2) }}/kg
                                    </span>
                                </td>
                                <td>
                                    <span class="date-muted">
                                        {{ \Carbon\Carbon::parse($history->changed_at)->format('M d, Y h:i A') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-history">
                                        No milling fee changes recorded yet.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="history-footer">
            <p class="history-note">
                Showing the latest 5 fee updates.
            </p>

            <a href="{{ route('owner.milling-fee-history') }}" class="btn-history">
                View All History →
            </a>
        </div>
    </div>

</div>

<script>
    function updateSmsToggle(toggle) {
        const hiddenInput = document.getElementById('smsHidden');
        const toggleText = document.getElementById('toggleText');

        hiddenInput.value = toggle.checked ? '1' : '0';
        toggleText.innerText = toggle.checked
            ? '{{ $smsEnabled === "1" ? "Turned On" : "Pending: Turn On" }}'
            : '{{ $smsEnabled === "0" ? "Turned Off" : "Pending: Turn Off" }}';
    }
</script>

@endsection
