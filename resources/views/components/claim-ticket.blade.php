@php
    $deliveredAt = $delivery->delivered_at
        ? \Carbon\Carbon::parse($delivery->delivered_at)
        : null;
    $recordedBy = $delivery->staff?->name;
@endphp

<style>
    .claim-ticket-page {
        max-width: 420px;
        margin: 0 auto;
    }

    .claim-ticket-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .claim-ticket-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        background: #fff;
        color: #172033;
        text-decoration: none;
        font-weight: 700;
    }

    .claim-ticket-action.primary {
        border-color: #1f7a3f;
        background: #1f7a3f;
        color: #fff;
    }

    .claim-ticket {
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
        padding: 22px 20px 18px;
        border: 1px solid #d6dce5;
        border-radius: 14px;
        background: #fff;
        color: #111827;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .10);
        font-family: Arial, Helvetica, sans-serif;
    }

    .claim-ticket * {
        box-sizing: border-box;
    }

    .claim-ticket-header {
        text-align: center;
    }

    .claim-ticket-brand-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        text-align: left;
    }

    .claim-ticket-rice-icon {
        width: 35px;
        height: 42px;
        flex: 0 0 auto;
        color: #245c28;
    }

    .claim-ticket-mill {
        display: block;
        margin: 0;
        color: #245c28;
        font-size: 16px;
        font-weight: 800;
        letter-spacing: .02em;
        line-height: 1.15;
        text-align: center;
    }

    .claim-ticket-brand {
        margin-top: 3px;
        color: #475569;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .18em;
        text-align: center;
    }

    .claim-ticket-title {
        margin: 10px 0 0;
        font-size: 20px;
        font-weight: 900;
        letter-spacing: .06em;
    }

    .claim-ticket-rule {
        margin: 13px 0;
        border: 0;
        border-top: 2px solid #172033;
        opacity: 1;
    }

    .claim-ticket-queue-label {
        margin-bottom: 5px;
        text-align: center;
        color: #475569;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .12em;
    }

    .claim-ticket-queue {
        padding: 7px 10px 9px;
        border: 2px solid #172033;
        border-radius: 10px;
        text-align: center;
        font-size: 48px;
        font-weight: 900;
        letter-spacing: .02em;
        line-height: 1;
    }

    .claim-ticket-section-title {
        margin: 14px 0 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #172033;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .08em;
    }

    .claim-ticket-row {
        display: grid;
        grid-template-columns: minmax(0, 42fr) minmax(0, 58fr);
        gap: 8px;
        align-items: baseline;
        min-height: 27px;
        padding: 5px 0;
        border-bottom: 1px dashed #aab2bf;
        font-size: 11px;
        line-height: 1.25;
    }

    .claim-ticket-row:last-child {
        border-bottom: 0;
    }

    .claim-ticket-row span:first-child {
        color: #475569;
    }

    .claim-ticket-row strong {
        overflow-wrap: anywhere;
        text-align: right;
        font-size: 11.5px;
    }

    .claim-ticket-estimate {
        margin-top: 14px;
        padding: 9px 10px 10px;
        border: 2px solid #172033;
        border-radius: 9px;
        text-align: center;
    }

    .claim-ticket-estimate-label {
        font-size: 9px;
        font-weight: 900;
        letter-spacing: .08em;
    }

    .claim-ticket-estimate-value {
        margin-top: 3px;
        font-size: 25px;
        font-weight: 900;
        line-height: 1.1;
    }

    .claim-ticket-estimate-note {
        margin-top: 3px;
        color: #64748b;
        font-size: 9px;
    }

    .claim-ticket-reminder {
        margin: 14px 0 0;
        padding-top: 11px;
        border-top: 2px solid #172033;
        text-align: center;
        font-size: 10px;
        font-weight: 900;
        line-height: 1.35;
    }

    .claim-ticket-reminder small {
        display: block;
        margin-top: 3px;
        color: #64748b;
        font-size: 9px;
        font-weight: 600;
    }

</style>

{{-- The driver shown as Printer 58 exposes a 48mm-wide page. --}}
<style id="claim-ticket-print-layout" media="print">
        @page {
            /* Use the selected printer form; custom CSS heights can split the footer. */
            size: auto;
            margin: 0;
        }

        html,
        body {
            width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            min-height: 0 !important;
            background: #fff !important;
        }

        .sidebar,
        .topbar,
        .claim-ticket-actions,
        .no-print {
            display: none !important;
        }

        .app-wrapper,
        .main-content,
        .content {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            max-width: 48mm !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .claim-ticket-page {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            width: 100% !important;
            max-width: 48mm !important;
            margin: 0 !important;
        }

        .claim-ticket {
            width: 100% !important;
            margin: 0 !important;
            padding: 4mm 3mm 3mm !important;
            overflow: visible !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            color: #000 !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }

        .claim-ticket-rice-icon {
            width: 6mm !important;
            height: 8mm !important;
            color: #000 !important;
        }

        .claim-ticket-brand-row { gap: 2mm; }

        .claim-ticket .claim-ticket-mill {
            margin: 0;
            font-size: 12px;
            letter-spacing: 0;
        }

        .claim-ticket-brand { font-size: 8px; }
        .claim-ticket-title { margin-top: 6px; font-size: 15px; }
        .claim-ticket-rule { margin: 7px 0; }
        .claim-ticket-queue-label { font-size: 8px; margin-bottom: 3px; }
        .claim-ticket-queue { padding: 4px; font-size: 30px; }
        .claim-ticket-section-title {
            margin-top: 8px;
            padding-bottom: 3px;
            font-size: 8px;
        }

        .claim-ticket-row {
            gap: 4px;
            min-height: 0;
            padding: 3px 0;
            font-size: 9px;
            break-inside: avoid;
        }

        .claim-ticket-row strong { min-width: 0; font-size: 9px; }
        .claim-ticket-estimate { margin-top: 8px; padding: 5px; }
        .claim-ticket-estimate-label { font-size: 8px; }
        .claim-ticket-estimate-value { font-size: 20px; }
        .claim-ticket-estimate-note { font-size: 8px; }
        .claim-ticket .claim-ticket-reminder {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            margin: 8px 0 0;
            padding-top: 6px;
            color: #000;
            font-size: 8px;
        }

        .claim-ticket-reminder small { font-size: 8px; }

        .claim-ticket-mill,
        .claim-ticket-brand,
        .claim-ticket-row span:first-child,
        .claim-ticket-estimate-note,
        .claim-ticket-reminder small {
            color: #000 !important;
        }
</style>

<div class="claim-ticket-actions no-print">
    <a href="{{ $backUrl }}" class="claim-ticket-action">
        <i data-lucide="arrow-left"></i>
        Back
    </a>

    <button type="button" onclick="window.print()" class="claim-ticket-action primary">
        <i data-lucide="printer"></i>
        Print Claim Ticket
    </button>
</div>

<div class="claim-ticket-page">
    <article class="claim-ticket" aria-label="Claim ticket">
        <header class="claim-ticket-header">
            <div class="claim-ticket-brand-row">
                <svg class="claim-ticket-rice-icon"
                     viewBox="0 0 32 42"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     aria-hidden="true">
                    <path d="M15.5 39V11M15.5 23C10 20 7 15.5 6 10M15.5 29C21 26.5 24.5 22.5 26 17"
                          stroke="currentColor"
                          stroke-width="2.2"
                          stroke-linecap="round"/>
                    <path d="M15.5 18C20 15.5 22.5 11.5 23 7M15.5 14C12 12 10 8.5 9.5 5"
                          stroke="currentColor"
                          stroke-width="2.2"
                          stroke-linecap="round"/>
                    <ellipse cx="8.2" cy="8.2" rx="2.2" ry="4" transform="rotate(-36 8.2 8.2)" fill="currentColor"/>
                    <ellipse cx="5.2" cy="13.8" rx="2.1" ry="3.8" transform="rotate(-40 5.2 13.8)" fill="currentColor"/>
                    <ellipse cx="11" cy="16.2" rx="2.1" ry="3.8" transform="rotate(-42 11 16.2)" fill="currentColor"/>
                    <ellipse cx="23.7" cy="9.3" rx="2.1" ry="3.8" transform="rotate(39 23.7 9.3)" fill="currentColor"/>
                    <ellipse cx="27" cy="14.7" rx="2.1" ry="3.8" transform="rotate(42 27 14.7)" fill="currentColor"/>
                    <ellipse cx="22.1" cy="18.8" rx="2.1" ry="3.8" transform="rotate(44 22.1 18.8)" fill="currentColor"/>
                </svg>
                <div>
                    <h1 class="claim-ticket-mill">JK Diez Rice Mill</h1>
                    <div class="claim-ticket-brand">PALAYTRACK</div>
                </div>
            </div>
            <div class="claim-ticket-title">CLAIM TICKET</div>
        </header>

        <hr class="claim-ticket-rule">

        <div class="claim-ticket-queue-label">MILLING QUEUE NO.</div>
        <div class="claim-ticket-queue">#{{ $delivery->queue_number }}</div>

        <div class="claim-ticket-section-title">TICKET DETAILS</div>
        <div class="claim-ticket-row">
            <span>Delivery ID</span>
            <strong>{{ $delivery->delivery_id }}</strong>
        </div>
        <div class="claim-ticket-row">
            <span>Date</span>
            <strong>{{ $deliveredAt?->format('M d, Y') ?? 'N/A' }}</strong>
        </div>
        <div class="claim-ticket-row">
            <span>Time</span>
            <strong>{{ $deliveredAt?->format('g:i A') ?? 'N/A' }}</strong>
        </div>

        <div class="claim-ticket-section-title">CLIENT INFORMATION</div>
        <div class="claim-ticket-row">
            <span>Name</span>
            <strong>{{ $delivery->client_name }}</strong>
        </div>
        <div class="claim-ticket-row">
            <span>Contact</span>
            <strong>{{ $delivery->contact_number }}</strong>
        </div>

        <div class="claim-ticket-section-title">PALAY DELIVERY</div>
        <div class="claim-ticket-row">
            <span>Rice Type</span>
            <strong>{{ $delivery->riceType->name ?? 'N/A' }}</strong>
        </div>
        <div class="claim-ticket-row">
            <span>Number of Sacks</span>
            <strong>{{ rtrim(rtrim(number_format((float) $delivery->sacks, 2, '.', ''), '0'), '.') }}</strong>
        </div>
        <div class="claim-ticket-row">
            <span>Palay Weight</span>
            <strong>{{ number_format($delivery->palay_weight, 2) }} kg</strong>
        </div>

        <div class="claim-ticket-estimate">
            <div class="claim-ticket-estimate-label">ESTIMATED MILLED RICE</div>
            <div class="claim-ticket-estimate-value">{{ number_format($delivery->estimated_rice, 2) }} kg</div>
            <div class="claim-ticket-estimate-note">Actual output may vary.</div>
        </div>

        @if($recordedBy)
            <div class="claim-ticket-row" style="margin-top: 8px;">
                <span>Recorded By</span>
                <strong>{{ $recordedBy }}</strong>
            </div>
        @endif

        <p class="claim-ticket-reminder">
            PRESENT THIS TICKET WHEN CLAIMING YOUR RICE
            <small>Please keep this ticket safe.</small>
        </p>
    </article>
</div>
