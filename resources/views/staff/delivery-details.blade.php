@extends('layouts.staff')

@section('content')
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

    .back-btn {
        min-height: 46px;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
    }

    .alert-custom {
        border: none;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 0.95rem;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
    }

    .section-card {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.3;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 22px;
    }

    .info-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 14px 16px;
    }

    .info-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.5;
    }

    .info-item.full {
        grid-column: 1 / -1;
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
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 14px;
        min-height: 52px;
        padding: 14px 16px;
        color: #111827;
        box-shadow: none;
    }

    .custom-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .field-label {
        font-size: 0.92rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
        display: block;
    }

    .action-btn {
        min-height: 46px;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
    }

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
        border: none;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .action-card.equal-height {
        height: 100%;
    }

    .action-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .action-card-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .action-card-body {
        display: flex;
        flex-direction: column;
        gap: 18px;
        height: 100%;
    }

    .action-card-top {
        margin-bottom: 8px;
    }

    .action-btn-lg {
        min-height: 58px;
        border-radius: 16px;
        font-size: 1.02rem;
        font-weight: 800;
        padding: 14px 18px;
    }

    .btn-dark-soft {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #fff;
        border: none;
        box-shadow: 0 10px 20px rgba(17, 24, 39, 0.18);
    }

    .btn-dark-soft:hover {
        color: #fff;
    }

    .payment-status-box {
        background: #f8fafc;
        border: 1px solid #dbe3ee;
        border-radius: 16px;
        padding: 16px 18px;
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
        border-radius: 18px;
        padding: 20px;
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
        min-height: 38px;
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

/* mobile fix */
@media (max-width: 576px) {
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
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Delivery Details</h1>
        <p class="page-subtitle">View and process the selected delivery record.</p>
    </div>

    <a href="/staff/deliveries" class="btn btn-outline-secondary back-btn">
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

@if($errors->any())
    <div class="alert alert-danger alert-custom mb-4">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="section-card">
    <h2 class="section-title">Delivery Information</h2>
    <p class="section-subtitle">Basic details and milling information for this delivery.</p>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Delivery ID</div>
            <div class="info-value">{{ $delivery->delivery_id }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Queue Number</div>
            <div class="info-value">#{{ $delivery->queue_number }}</div>
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
            <div class="info-label">Processed By</div>
            <div class="info-value">{{ $delivery->staff?->name ?? 'N/A' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Rice Type</div>
            <div class="info-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">Status</div>
            <div class="info-value">
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

        <div class="info-item">
            <div class="info-label">Delivered At</div>
            <div class="info-value">
                {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y h:i A') : 'N/A' }}
            </div>
        </div>

        <div class="info-item full">
            <div class="info-label">Notes</div>
            <div class="info-value">{{ $delivery->notes ?: 'No notes provided.' }}</div>
        </div>
    </div>
</div>

<div class="section-card">
    <h2 class="section-title">Update Delivery Status</h2>
    <p class="section-subtitle">Change the current status of this delivery.</p>

    <form method="POST" action="{{ url('/staff/delivery-status/' . $delivery->id) }}">
        @csrf

        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="field-label">Status</label>
                <select name="status" class="form-select custom-select" required>
                    <option value="">Select Status</option>
                    <option value="pending" {{ $delivery->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $delivery->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $delivery->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary action-btn w-100">
                    Update Status
                </button>
            </div>
        </div>
    </form>
</div>

@if($canEditActualRice)
    <div class="section-card">
        <h2 class="section-title">Update Actual Milled Rice</h2>
        <p class="section-subtitle">Record the actual milled rice output for inventory tracking.</p>

        <form method="POST" action="{{ url('/staff/actual-rice/' . $delivery->id) }}">
            @csrf

            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="field-label">Actual Milled Rice (kg)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="actual_rice"
                        class="form-control custom-input"
                        placeholder="Enter actual milled rice (kg)"
                        value="{{ old('actual_rice', $delivery->actual_rice) }}"
                        required
                    >
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-success action-btn w-100">
                        Save Actual Milled Rice
                    </button>
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
    <div class="section-card">
        <h2 class="section-title">Actual Milled Rice Record</h2>
        <p class="section-subtitle">Recorded actual milled rice output for this delivery.</p>

        @if($delivery->actual_rice !== null)
            <div class="helper-box helper-dark mb-3">
                <strong>Actual Milled Rice:</strong> {{ number_format($delivery->actual_rice, 2) }} kg
            </div>

            <div class="helper-box {{ $varianceClass }} mb-0">
                <strong>Variance:</strong>
                {{ $variance > 0 ? '+' : '' }}{{ number_format($variance, 2) }} kg
                ({{ $varianceLabel }})
            </div>
        @else
            <div class="helper-box helper-warning mb-0">
                Actual milled rice can only be entered when the delivery status is <strong>Processing</strong>.
            </div>
        @endif
    </div>
@endif

<div class="section-card">
    <h2 class="section-title">Log Farmer Notification</h2>

    @php
        $smsEnabled = \App\Models\Setting::getValue('sms_enabled', '0');
    @endphp

    <div class="helper-box helper-dark">
        <strong>Latest SMS Status:</strong>

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

    @if($smsEnabled === '1')
        <div class="helper-box helper-dark">
           SMS notification is enabled. When the delivery becomes <strong>Completed</strong>, the system automatically sends a real SMS through Semaphore and logs it below.
        </div>
    @else
        <div class="helper-box helper-warning">
            SMS notification is disabled. Please notify the farmer manually and record it below.
        </div>
    @endif

    @if((string) $smsEnabled !== '1' && !$latestNotification)
    <p class="section-subtitle">Record how and when the farmer was notified.</p>

    <form method="POST" action="{{ url('/staff/delivery-notification/' . $delivery->id) }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-3">
                <label class="field-label">Method</label>
                <select name="method" class="form-select custom-select" required>
                    <option value="">Select Method</option>
                    <option value="call" {{ old('method') == 'call' ? 'selected' : '' }}>Call</option>
                    <option value="text" {{ old('method') == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="in_person" {{ old('method') == 'in_person' ? 'selected' : '' }}>In Person</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="field-label">Notification Status</label>
                <select name="notification_status" class="form-select custom-select" required>
                    <option value="">Select Status</option>
                    <option value="sent" {{ old('notification_status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="reached" {{ old('notification_status') == 'reached' ? 'selected' : '' }}>Reached</option>
                    <option value="failed" {{ old('notification_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="field-label">Notified At</label>
                <input
                    type="datetime-local"
                    name="notified_at"
                    class="form-control custom-input"
                    value="{{ old('notified_at') }}"
                    required
                >
            </div>

            <div class="col-12">
                <label class="field-label">Remarks</label>
                <textarea
                    name="remarks"
                    class="form-control custom-textarea"
                    rows="3"
                    placeholder="Optional remarks..."
                >{{ old('remarks') }}</textarea>
            </div>

                               <div class="col-md-3">
                <button type="submit" class="btn btn-info text-white action-btn w-100">
                    Save Notification
                </button>
            </div>
        </div>
    </form>
@endif
</div>

<div class="section-card">
    <h2 class="section-title">Notification History</h2>
    <p class="section-subtitle">Previous notification logs for this delivery.</p>

    @if($latestNotification)
        @if($latestNotification->notification_status === 'failed')
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
        @else
            <div class="table-responsive">
                <table class="table table-soft align-middle">
                    <thead>
                        <tr>
                            <th>Notified At</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                @if($latestNotification->notified_at)
                                    {{ \Carbon\Carbon::parse($latestNotification->notified_at)->format('M d, Y h:i A') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', $latestNotification->method)) }}
                            </td>

                            <td>
                                <span class="sms-status-badge
                                    {{ $latestNotification->notification_status === 'sent' ? 'sms-sent' : '' }}
                                    {{ $latestNotification->notification_status === 'reached' ? 'sms-reached' : '' }}
                                ">
                                    {{ ucfirst($latestNotification->notification_status) }}
                                </span>
                            </td>

                            <td>
                                {{ $latestNotification->remarks ?: '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="empty-state">
            No notification logs yet.
        </div>
    @endif
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="action-card equal-height">
            <div class="action-card-body">
                <div class="action-card-top">
                    <h2 class="action-card-title">Claim Stub Access</h2>
                    <p class="action-card-subtitle">
                        Open and review the printable claim stub for this delivery.
                    </p>
                </div>

                <a href="{{ url('/staff/claim-stub/' . $delivery->id) }}"
                   class="btn btn-main action-btn-lg w-100 d-flex align-items-center justify-content-center gap-2 mt-auto">
                    <i data-lucide="file-text"></i>
                    Open Claim Stub
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="action-card equal-height">
            <div class="action-card-body">
                <div class="action-card-top">
                    <h2 class="action-card-title">Billing & Payment (POS)</h2>
                    <p class="action-card-subtitle">
                        Review billing details, record payment, and view the receipt when available.
                    </p>
                </div>

                @if($delivery->transaction)
                    <div class="d-flex flex-column gap-3 mt-auto">
                        <div class="payment-status-box">
                            <p class="payment-status-label mb-0">Payment Status</p>
                            <span class="payment-pill">Already Paid</span>
                        </div>

                        <a href="{{ url('/staff/receipt/' . $delivery->id) }}"
                           class="btn btn-main action-btn-lg w-100 d-flex justify-content-center align-items-center gap-2">
                            <i data-lucide="file-text"></i>
                            View Receipt
                        </a>
                    </div>
                @elseif($delivery->status === 'completed')
                    <a href="{{ url('/staff/pos/' . $delivery->id) }}"
                       class="btn btn-success action-btn-lg w-100 mt-auto">
                        Proceed to Payment
                    </a>
                @else
                    <div class="helper-box helper-warning mb-0 mt-auto">
                        Complete delivery first before payment.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="action-card">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="claim-highlight">
                <div class="claim-highlight-title">Claim Delivery</div>
                <p class="claim-highlight-text">
                    Finalize the release of the delivery to the customer after all processing and payment steps are complete.
                </p>
            </div>
        </div>

        <div class="col-lg-4">
            @if($delivery->status === 'claimed')
                <div class="helper-box helper-dark mb-0">
                    This delivery has already been claimed.
                </div>
            @elseif($delivery->status === 'completed' && $delivery->transaction)
                <form method="POST" action="{{ url('/staff/claim-delivery/' . $delivery->id) }}"
                    data-confirm-title="Mark as Claimed?"
                    data-confirm-message="Confirm that the milled rice has been released to the customer."
                    data-confirm-button="Mark as Claimed">
    @csrf

    <button type="submit"
            class="btn btn-dark-soft action-btn-lg w-100">
        Mark as Claimed
    </button>
</form>
            @elseif($delivery->status === 'completed' && !$delivery->transaction)
                <div class="helper-box helper-warning mb-0">
                    Payment must be completed first before this delivery can be claimed.
                </div>
            @else
                <div class="helper-box helper-warning mb-0">
                    Delivery must be completed first before it can be claimed.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
