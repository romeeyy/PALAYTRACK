{{-- Match the claim ticket's POS58 layout; the driver controls paper length. --}}
<style media="print">
    @page {
        size: auto;
        margin: 0;
    }

    html,
    body {
        width: 100% !important;
        min-width: 0 !important;
        height: auto !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .sidebar,
    .topbar,
    .receipt-page-header,
    .no-print {
        display: none !important;
    }

    .app-wrapper,
    .main-content,
    .content,
    .receipt-page,
    .receipt-preview-area,
    .thermal-wrapper {
        display: block !important;
        width: 100% !important;
        min-width: 0 !important;
        max-width: 48mm !important;
        height: auto !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
        transform: none !important;
        overflow: visible !important;
    }

    .thermal-receipt {
        box-sizing: border-box;
        width: 100% !important;
        padding: 4mm 3mm 3mm !important;
        border: 0 !important;
        background: #fff !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .thermal-receipt,
    .thermal-receipt * {
        color: #000 !important;
        box-shadow: none !important;
        overflow-wrap: anywhere;
    }

    .thermal-receipt .mill-name { font-size: 12px; font-weight: 900; }
    .thermal-receipt .receipt-title { font-size: 10px; font-weight: 900; }
    .thermal-receipt .receipt-address { font-size: 9px; font-weight: 700; }
    .thermal-receipt .line { margin: 6px 0; }

    .thermal-receipt .row-line,
    .thermal-receipt .total-row {
        display: grid;
        grid-template-columns: minmax(0, 42fr) minmax(0, 58fr);
        gap: 4px;
        align-items: baseline;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.35;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .thermal-receipt .row-line span,
    .thermal-receipt .total-row span { min-width: 0; }
    .thermal-receipt .total-row span:last-child { text-align: right; }
    .thermal-receipt .label { font-size: 9px; font-weight: 700; break-after: avoid; }
    .thermal-receipt .value { font-size: 10px; font-weight: 700; }
    .thermal-receipt .total-box { margin: 6px 0; padding: 6px 0; }
    .thermal-receipt .total-row { font-size: 11px; }
    .thermal-receipt .footer { margin-top: 6px; font-size: 9px; font-weight: 700; line-height: 1.35; }

    .thermal-receipt .total-box,
    .thermal-receipt .footer {
        break-inside: avoid;
        page-break-inside: avoid;
    }
</style>
