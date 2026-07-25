@extends('layouts.owner')

@section('content')

@php
    $latestNotification = $delivery->notifications->sortByDesc('notified_at')->first();
    $smsStatus = $latestNotification?->notification_status ?? null;

    $actualRice = $delivery->actual_rice;
    $difference = $actualRice !== null ? $actualRice - $delivery->estimated_rice : null;
@endphp

<style>
    .page-header { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; margin-bottom:20px; }

    .details-page-title {
        font-size: 1.8rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .details-page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 42px;
        padding: 9px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 11px;
        background: #ffffff;
        color: #111827;
        text-decoration: none;
        font-weight: 700;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #111827;
    }

    .section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 18px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .section-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 5px;
    }

    .section-subtitle { color:#64748b; font-size:.88rem; margin:0 0 15px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 14px;
        min-height: 74px;
    }

    .info-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: 0.92rem;
        color: #0f172a;
        font-weight: 700;
        word-break: break-word;
    }

    .note-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 14px;
        color: #111827;
        font-size: .9rem;
        line-height: 1.6;
    }

    .status-pill,
    .sms-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        font-weight: 900;
        font-size: 0.86rem;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-pill.pending { background: #fef3c7; color: #a16207; }
    .status-pill.processing { background: #dbeafe; color: #1d4ed8; }
    .status-pill.completed { background: #dcfce7; color: #15803d; }
    .status-pill.claimed { background: #ede9fe; color: #6d28d9; }

    .sms-sent { background: #dcfce7; color: #15803d; }
    .sms-failed { background: #fee2e2; color: #b91c1c; }
    .sms-queued { background: #fef3c7; color: #a16207; }
    .sms-none { background: #e5e7eb; color: #475569; }

    .highlight-box {
        border-radius: 12px;
        padding: 14px 16px;
        background: #eef6ea;
        border: 1px solid #d9ead3;
        min-height: 104px;
    }

    .highlight-label {
        color: #475569;
        font-size: 0.9rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .highlight-value {
        color: #2f5d1e;
        font-size: 1.55rem;
        font-weight: 900;
        line-height: 1;
    }

    .difference-box {
        border-radius: 12px;
        padding: 14px 16px;
        background: #fff7ed;
        border: 1px solid #fdba74;
        color: #9a3412;
        font-weight: 800;
        min-height: 104px;
    }

    .measurement-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .difference-label {
        color: #9a3412;
        font-size: .82rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .difference-value {
        color: #9a3412;
        font-size: 1.02rem;
        font-weight: 900;
        line-height: 1.35;
    }

    .support-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
        gap: 18px;
        align-items: stretch;
    }

    .support-grid .section-card {
        height: 100%;
        margin-bottom: 0;
    }

    .claim-card {
        display: flex;
        flex-direction: column;
    }

    .claim-card .btn-main {
        margin-top: auto;
    }

    .readonly-note {
        margin-top: 14px;
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .btn-main {
        min-height: 52px;
        border-radius: 12px;
        font-weight: 800;
        border: none;
        background: linear-gradient(135deg, #2f5d1e 0%, #3f7a28 100%);
        color: white;
        box-shadow: 0 10px 18px rgba(47, 93, 30, 0.18);
    }

    .btn-main:hover {
        background: linear-gradient(135deg, #274d19 0%, #35671f 100%);
        color: white;
    }

    @media (max-width: 992px) {
        .info-grid { grid-template-columns: repeat(2, 1fr); }
        .measurement-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .support-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .info-grid { grid-template-columns: 1fr; }
        .measurement-grid { grid-template-columns: 1fr; }
        .details-page-title { font-size: 1.7rem; }
        .section-card { padding: 20px; }
    }
</style>

<div class="page-header">
    <div>
        <h1 class="details-page-title">Delivery Details</h1>
        <p class="details-page-subtitle">
            View the complete delivery and milling record.
        </p>
    </div>
    <a href="/owner/deliveries" class="back-btn">
        <i data-lucide="arrow-left"></i>
        <span>Back to Deliveries</span>
    </a>
</div>

<div class="section-card">
    <h2 class="section-title">Delivery Information</h2>
    <p class="section-subtitle">Basic delivery, client, and milling information.</p>

    <div class="info-grid">
        <div class="info-box">
            <div class="info-label">Delivery ID</div>
            <div class="info-value">{{ $delivery->delivery_id }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Daily Queue Number</div>
            <div class="info-value">#{{ $delivery->queue_number }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Delivery Date</div>
            <div class="info-value">
                {{ $delivery->delivered_at ? \Carbon\Carbon::parse($delivery->delivered_at)->format('m/d/Y') : 'N/A' }}
            </div>
        </div>

        <div class="info-box">
            <div class="info-label">Client Name</div>
            <div class="info-value">{{ $delivery->client_name }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Contact Number</div>
            <div class="info-value">{{ $delivery->contact_number }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Recorded By</div>
            <div class="info-value">
                {{ $delivery->staff?->name ?? 'Unknown account' }}
                @if($delivery->staff?->role)
                    <span class="sub-text">({{ ucfirst($delivery->staff->role) }})</span>
                @endif
            </div>
        </div>

        <div class="info-box">
            <div class="info-label">Rice Type</div>
            <div class="info-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Number of Sacks</div>
            <div class="info-value">{{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }}</div>
        </div>

        <div class="info-box">
            <div class="info-label">Total Palay Weight</div>
            <div class="info-value">{{ number_format($delivery->palay_weight, 2) }} kg</div>
        </div>

        <div class="info-box">
            <div class="info-label">Recovery Rate</div>
            <div class="info-value">{{ number_format($delivery->recovery_rate, 2) }}%</div>
        </div>

        <div class="info-box">
            <div class="info-label">Estimated Milled Rice</div>
            <div class="info-value">{{ number_format($delivery->estimated_rice, 2) }} kg</div>
        </div>

        <div class="info-box">
            <div class="info-label">Current Status</div>
            <span class="status-pill {{ $delivery->status }}">
                <span class="status-dot"></span>
                {{ ucfirst($delivery->status) }}
            </span>
        </div>
    </div>

    <div class="mt-4">
        <div class="info-label">Notes</div>
        <div class="note-box">{{ $delivery->notes ?: 'No notes provided.' }}</div>
    </div>
</div>

<div class="section-card">
    <h2 class="section-title">Milled Rice Measurement</h2>
    <p class="section-subtitle">Estimated and actual output comparison for this delivery.</p>

    <div class="measurement-grid">
        <div class="highlight-box">
            <div class="highlight-label">Estimated Milled Rice</div>
            <div class="highlight-value">{{ number_format($delivery->estimated_rice, 2) }} kg</div>
        </div>

        <div class="highlight-box">
            <div class="highlight-label">Actual Milled Rice</div>
            <div class="highlight-value">
                {{ $actualRice !== null ? number_format($actualRice, 2) . ' kg' : 'Not yet recorded' }}
            </div>
        </div>

        <div class="difference-box">
            <div class="difference-label">Difference</div>
            <div class="difference-value">
                @if($actualRice !== null)
                    {{ number_format($difference, 2) }} kg
                    @if($difference > 0)
                        (Above estimate)
                    @elseif($difference < 0)
                        (Below estimate)
                    @else
                        (Matches estimate)
                    @endif
                @else
                    Not yet available
                @endif
            </div>
        </div>
    </div>

    <p class="readonly-note">
        This section is read-only for the owner. Actual milled rice and inventory updates are handled by staff.
    </p>
</div>

<div class="support-grid">
        <div class="section-card">
            <h2 class="section-title">Farmer Notification</h2>
            <p class="section-subtitle">Latest notification details for customer pickup.</p>

            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">SMS Status</div>

                    @if($smsStatus)
                        <span class="sms-status-badge
                            {{ $smsStatus === 'sent' ? 'sms-sent' : '' }}
                            {{ $smsStatus === 'failed' ? 'sms-failed' : '' }}
                            {{ $smsStatus === 'queued' ? 'sms-queued' : '' }}
                        ">
                            {{ ucfirst($smsStatus) }}
                        </span>
                    @else
                        <span class="sms-status-badge sms-none">No SMS yet</span>
                    @endif
                </div>

                <div class="info-box">
                    <div class="info-label">Date & Time Notified</div>
                    <div class="info-value">
                        {{ $latestNotification?->notified_at ? \Carbon\Carbon::parse($latestNotification->notified_at)->format('M d, Y h:i A') : 'Not yet logged' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Notification Method</div>
                    <div class="info-value">
                        {{ $latestNotification?->method ? ucwords(str_replace('_', ' ', $latestNotification->method)) : 'Not yet logged' }}
                    </div>
                </div>
            </div>

            <p class="readonly-note">
                Notification records are shown for monitoring and verification. Staff or the system handles notification actions.
            </p>
        </div>

        <div class="section-card claim-card">
            <h2 class="section-title">Claim Stub Access</h2>

            <p class="section-subtitle">
                Open the claim stub for verification or reprinting.
            </p>

            <a href="/owner/claim-stub/{{ $delivery->id }}"
               class="btn btn-main w-100 d-flex align-items-center justify-content-center gap-2">
                <i data-lucide="file-text"></i>
                Open Claim Stub
            </a>
        </div>
</div>

@endsection
