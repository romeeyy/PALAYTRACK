<style>
@media screen {
    html[data-accent="classic"] {
        --user-accent: #168344;
        --user-accent-dark: #125f34;
        --user-accent-soft: #eaf7ef;
        --user-accent-ring: rgba(22, 131, 68, .20);
        --user-sidebar-start: #0f3d1c;
        --user-sidebar-main: #166534;
    }

    html[data-accent="forest"] {
        --user-accent: #356b3d;
        --user-accent-dark: #244c2a;
        --user-accent-soft: #edf4ee;
        --user-accent-ring: rgba(53, 107, 61, .22);
        --user-sidebar-start: #18351f;
        --user-sidebar-main: #294f2f;
    }

    html[data-accent="emerald"] {
        --user-accent: #059669;
        --user-accent-dark: #047857;
        --user-accent-soft: #ecfdf5;
        --user-accent-ring: rgba(5, 150, 105, .22);
        --user-sidebar-start: #064e3b;
        --user-sidebar-main: #047857;
    }

    html[data-accent="olive"] {
        --user-accent: #6b7f2a;
        --user-accent-dark: #4d5f1f;
        --user-accent-soft: #f3f6e7;
        --user-accent-ring: rgba(107, 127, 42, .23);
        --user-sidebar-start: #29330f;
        --user-sidebar-main: #53651f;
    }

    html[data-accent="sage"] {
        --user-accent: #4f7d67;
        --user-accent-dark: #385c4a;
        --user-accent-soft: #edf5f0;
        --user-accent-ring: rgba(79, 125, 103, .23);
        --user-sidebar-start: #203a2f;
        --user-sidebar-main: #416b57;
    }

    html[data-accent="palay"] {
        --user-accent: #65a30d;
        --user-accent-dark: #4d7c0f;
        --user-accent-soft: #f7fee7;
        --user-accent-ring: rgba(101, 163, 13, .23);
        --user-sidebar-start: #365314;
        --user-sidebar-main: #4d7c0f;
    }

    html[data-accent] .sidebar {
        background: linear-gradient(145deg,
            var(--user-sidebar-start) 0%,
            var(--user-sidebar-main) 25%,
            var(--user-sidebar-main) 75%,
            var(--user-sidebar-start) 100%) !important;
    }

    /* Keep navigation-back actions consistent across modules. */
    html[data-accent] .main-content :is(.back-btn, .audit-back, .receipt-actions .btn-outline-secondary, .claim-ticket-action:not(.primary)) {
        min-height: 40px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        padding: 8px 13px !important;
        border: 1px solid color-mix(in srgb, var(--user-accent) 30%, #d1d5db) !important;
        border-radius: 10px !important;
        background: #fff !important;
        color: var(--user-accent-dark) !important;
        font-size: .88rem !important;
        font-weight: 800 !important;
        text-decoration: none !important;
        box-shadow: none !important;
        transition: background .2s ease, border-color .2s ease, color .2s ease !important;
    }

    html[data-accent] .main-content :is(.back-btn, .audit-back, .receipt-actions .btn-outline-secondary, .claim-ticket-action:not(.primary)):hover {
        background: var(--user-accent-soft) !important;
        border-color: var(--user-accent) !important;
        color: var(--user-accent-dark) !important;
    }

    html[data-accent] .main-content :is(.back-btn, .audit-back) svg { width: 20px; height: 20px; }

    html[data-accent] .main-content :is(.filter-btn, .filter-reset, .btn-filter, .btn-reset, .audit-button, .btn-main) {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }
    html[data-accent] .main-content :is(.filter-btn, .filter-reset, .btn-filter, .btn-reset, .audit-button, .btn-main) :is(svg, i) {
        width: 17px;
        height: 17px;
        flex: 0 0 17px;
    }

    html[data-accent] .main-content :is(.alert-custom.alert-success, .alert-success-custom, .alert.alert-success, .alert-soft) {
        background: var(--user-accent-soft) !important;
        border: 1px solid color-mix(in srgb, var(--user-accent) 18%, #ffffff) !important;
        color: var(--user-accent-dark) !important;
    }

    /* Warm harvest amber keeps warnings distinct without the harsh orange tone. */
    html[data-accent] .main-content :is(.helper-warning, .difference-box, .result-helper-warning, .alert-custom.alert-warning) {
        background: #fff8e6 !important;
        border-color: #e6c875 !important;
        color: #8a5a00 !important;
    }
    html[data-accent] .main-content :is(.alert.alert-danger, .alert-error-custom) {
        background: #fff1f2 !important;
        border: 1px solid #fecdd3 !important;
        color: #b42318 !important;
    }
    html[data-accent] .main-content :is(.alert.alert-info, .alert-info-custom) {
        background: var(--user-accent-soft) !important;
        border: 1px solid color-mix(in srgb, var(--user-accent) 18%, #ffffff) !important;
        color: var(--user-accent-dark) !important;
    }
    html[data-accent] .main-content .delivery-actions-grid .action-card:nth-child(3) {
        border-top-color: #c9942f !important;
    }
    html[data-accent] .main-content .result-helper-warning .result-icon {
        background: #fff1c7 !important;
        color: #a66a00 !important;
    }
    html[data-accent] .main-content .workflow-hint svg {
        color: #c9942f !important;
    }

    /* Shared light surfaces keep every module on the same visual system. */
    html[data-accent] .main-content :is(
        .filter-card, .table-card, .report-card, .section-card,
        .form-card, .operations-card
    ) {
        background: linear-gradient(135deg, #ffffff 0%, #f1f8f2 100%);
        border-color: color-mix(in srgb, var(--user-accent) 18%, #d5e1d8);
        box-shadow: 0 14px 30px color-mix(in srgb, var(--user-accent) 10%, transparent);
    }

    html[data-accent] .main-content :is(
        .filter-card, .table-card, .report-card, .section-card,
        .form-card, .operations-card
    ) :is(.table-responsive, .table-scroll, .logs-wrapper) {
        border-radius: 14px;
    }

    html[data-accent] .main-content :is(
        .soft-table thead th, .inventory-table thead th
    ) {
        background: color-mix(in srgb, var(--user-accent-soft) 72%, #ffffff) !important;
        color: var(--user-accent-dark) !important;
    }

    /*
     * Primary actions must follow the selected accent. Module-specific views
     * historically used different class names and fixed green values.
     * Secondary, edit, danger, warning, and status controls stay semantic.
     */
    html[data-accent] .main-content :is(
        .btn-success, .btn-save, .save-theme,
        .quick-btn.btn-success, button.btn-success,
        .btn-main, .btn-print, .btn-filter, .add-btn,
        .claim-ticket-action.primary,
        .submit-btn, .modal-save, .audit-button-primary,
        .system-confirm-submit:not(.danger),
        .filter-btn:not(.btn-outline-secondary),
        .queue-tab.active, .tab.active
    ) {
        background: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
        color: #fff !important;
    }

    html[data-accent] .system-confirm-submit:not(.danger) {
        background: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
        color: #fff !important;
    }

    html[data-accent] .system-confirm-submit:not(.danger):hover {
        background: var(--user-accent-dark) !important;
        border-color: var(--user-accent-dark) !important;
    }

    html[data-accent] .main-content :is(
        .btn-success, .btn-save, .save-theme,
        .quick-btn.btn-success, button.btn-success,
        .btn-main, .btn-print, .btn-filter, .add-btn,
        .claim-ticket-action.primary,
        .submit-btn, .modal-save, .audit-button-primary,
        .system-confirm-submit:not(.danger),
        .filter-btn:not(.btn-outline-secondary),
        .queue-tab.active, .tab.active
    ):hover {
        background: var(--user-accent-dark) !important;
        border-color: var(--user-accent-dark) !important;
        color: #fff !important;
    }

    html[data-accent] .avatar-small { background-color: var(--user-accent-dark) !important; }

    html[data-accent] .main-content :is(.btn-outline-success,.details-btn) {
        background: transparent !important;
        border-color: var(--user-accent) !important;
        color: var(--user-accent-dark) !important;
    }

    html[data-accent] .main-content :is(.btn-outline-success,.details-btn):hover {
        background: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
        color: #fff !important;
    }

    html[data-accent] .main-content .queue-tab:not(.active):hover {
        background: var(--user-accent-soft) !important;
        color: var(--user-accent-dark) !important;
    }

    html[data-theme="dark"] {
        color-scheme: dark;
        --page-bg: #0f172a;
        --text-dark: #edf3fb;
        --text-muted: #9fb0c5;
        --border-soft: #334155;
        --shadow-soft: 0 14px 35px rgba(0, 0, 0, .24);
    }

    html[data-theme="dark"][data-accent] .sidebar {
        background: linear-gradient(155deg,
            color-mix(in srgb, var(--user-accent-dark) 42%, #0b1220) 0%,
            #111827 42%,
            #0b1220 100%) !important;
        border-color: #263449 !important;
        box-shadow: 8px 0 28px rgba(0, 0, 0, .28) !important;
    }

    html[data-theme="dark"] .sidebar::after {
        opacity: .08 !important;
    }

    html[data-theme="dark"] .sidebar .nav-link-custom {
        color: #cbd5e1 !important;
    }

    html[data-theme="dark"] .sidebar .nav-link-custom:hover {
        background: rgba(255, 255, 255, .08) !important;
        color: #fff !important;
    }

    html[data-theme="dark"][data-accent] .sidebar .nav-link-custom.active {
        background: color-mix(in srgb, var(--user-accent) 42%, #1e293b) !important;
        color: #fff !important;
        box-shadow: inset 3px 0 0 var(--user-accent), 0 6px 16px rgba(0, 0, 0, .16) !important;
    }

    html[data-theme="dark"] .sidebar :is(.nav-section-label,.sidebar-footer) {
        color: #94a3b8 !important;
        border-color: #334155 !important;
    }

    html[data-theme="dark"] :is(body,.main-content) {
        background: #0f172a !important;
        color: #e5edf7 !important;
    }

    html[data-theme="dark"] .main-content :is(
        .topbar,.profile-menu,.modal-content,.client-modal,
        .modal-box,.receipt-page,
        [class~="card"],[class$="-card"],[class$="-panel"],[class$="-shell"]
    ) {
        background-color: #172033 !important;
        background-image: none !important;
        border-color: #35445a !important;
        color: #e5edf7 !important;
        box-shadow: 0 10px 28px rgba(0,0,0,.20) !important;
    }

    /* Enforce a uniform dark surface for every module-specific card variant. */
    html[data-theme="dark"] .main-content :is(
        [class*="card"],[class*="panel"],[class*="section"],
        [class*="shell"],[class*="content"],[class*="container"]
    ) {
        background-color: #172033 !important;
        background-image: none !important;
        border-color: #35445a !important;
        color: #e5edf7 !important;
    }

    html[data-theme="dark"] .main-content [class$="-box"] {
        background-color: #111a2b !important;
        border-color: #35445a !important;
        color: #e5edf7 !important;
    }

    html[data-theme="dark"] .main-content :is(
        .filter-panel,.table-wrap,.table-shell-top,.security-settings,
        .queue-tabs,.tabs,.nav-tabs,.detail-item,.info-item,.meta-item,
        .quick-insight-item,.collection-summary-item,.collection-summary,
        .chart-card-body,.table-scroll-wrapper,.history-table-container,
        .settings-table-container,.modal-head,.modal-body,.staff-filter-row,.staff-filters,
        .filter-bar,.history-table-wrap,.history-table-scroll,
        .info-box,.summary-box,.security-box,.estimate-box,.preview-box,
        .note-box,.failed-notification-box,.updated-pill,.empty-icon,
        .today-summary-item,.helper-box.helper-dark
    ) {
        background: #111a2b !important;
        border-color: #35445a !important;
        color: #dce6f2 !important;
    }

    html[data-theme="dark"] .main-content :is(
        .today-summary-value,.today-summary-label,
        .helper-box.helper-dark,.helper-box.helper-dark strong,
        .meta-item,.meta-item strong
    ) {
        color: #dce6f2 !important;
    }

    html[data-theme="dark"] .main-content .today-summary-item {
        background-image: none !important;
        box-shadow: none !important;
    }

    html[data-theme="dark"] .main-content .signature-box {
        background: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }

    html[data-theme="dark"] .main-content .signature-line {
        border-color: #718096 !important;
        color: #edf3fb !important;
    }

    html[data-theme="dark"] .main-content .signature-label {
        color: #9fb0c5 !important;
    }

    /* Bootstrap and older staff-page surfaces that do not follow card naming. */
    html[data-theme="dark"] .main-content :is(
        .bg-white,.bg-light,.table-responsive,.list-group,.list-group-item,
        .dropdown-menu,.dropdown-item,.input-group-text,.accordion-item,
        .accordion-button,.offcanvas,.toast,.filter-grid,.form-section,
        .details-grid,.action-area,.notification-fields,.report-filters
    ) {
        background-color: #172033 !important;
        background-image: none !important;
        border-color: #35445a !important;
        color: #e5edf7 !important;
    }

    html[data-theme="dark"] .main-content :is(
        .form-control-plaintext,.form-check-label,.filter-label,.info-label,
        .summary-label,.table-label,.meta-label,.field-label
    ) {
        color: #b8c7da !important;
    }

    html[data-theme="dark"] .main-content :is(
        .btn-light,.btn-white,.reset-btn,.filter-reset,.btn-cancel,
        .btn-outline-dark,.btn-outline-primary
    ) {
        background: #172033 !important;
        border-color: #52637b !important;
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] .main-content hr {
        border-color: #42516a !important;
        opacity: 1;
    }

    html[data-theme="dark"] .main-content :is(input,select,textarea,.form-control,.form-select) {
        background: #101827 !important;
        border-color: #485970 !important;
        color: #f1f5f9 !important;
    }
    html[data-theme="dark"] .main-content :is(input,textarea)::placeholder { color: #8392a8 !important; }

    html[data-theme="dark"] .main-content :is(
        h1,h2,h3,h4,h5,h6,label,strong,
        [class$="-title"],[class$="-name"],[class$="-value"],
        .page-title,.card-title,.client-name,.staff-name,.rice-name
    ) { color: #edf3fb !important; }

    html[data-theme="dark"] .main-content :is(.date-main,.staff-email,.date-sub) {
        color: #b8c7da !important;
    }

    html[data-theme="dark"] .main-content :is(
        p,[class$="-subtitle"],[class$="-text"],[class$="-meta"],
        [class$="-unit"],.field-help,.sub-text,.light-text
    ) { color: #9fb0c5 !important; }

    html[data-theme="dark"] .main-content :is(table,thead,tbody,tr,th,td) {
        background-color: transparent !important;
        border-color: #35445a !important;
        color: #dce6f2 !important;
        box-shadow: none !important;
    }
    html[data-theme="dark"] .main-content table {
        --bs-table-bg: transparent;
        --bs-table-accent-bg: transparent;
        --bs-table-striped-bg: transparent;
        --bs-table-hover-bg: transparent;
        --bs-table-active-bg: transparent;
    }
    html[data-theme="dark"] .main-content .soft-table td.actions-column,
    html[data-theme="dark"] .main-content .soft-table td.actions-column .row-actions {
        background: transparent !important;
        box-shadow: none !important;
    }
    html[data-theme="dark"] .main-content .soft-table td.actions-column .details-btn {
        background: transparent !important;
        background-image: none !important;
        box-shadow: none !important;
    }
    html[data-theme="dark"] .main-content thead th { background: #202c40 !important; color: #edf3fb !important; }
    html[data-theme="dark"] .main-content tbody tr:hover { background: #1d293b !important; }

    html[data-theme="dark"] .main-content .history-table,
    html[data-theme="dark"] .main-content .history-table tbody,
    html[data-theme="dark"] .main-content .history-table tbody tr,
    html[data-theme="dark"] .main-content .history-table tbody td {
        background: #111a2b !important;
        border-color: #35445a !important;
        color: #cbd5e1 !important;
    }
    html[data-theme="dark"] .main-content .history-table tbody tr:hover,
    html[data-theme="dark"] .main-content .history-table tbody tr:hover td {
        background: #1d293b !important;
    }

    html[data-theme="dark"] .main-content :is(.pill-type,.type-pill) {
        background: #172d52 !important;
        color: #93c5fd !important;
    }
    html[data-theme="dark"] .main-content .fee-old {
        background: #451a1a !important;
        border-color: #7f1d1d !important;
        color: #fca5a5 !important;
    }
    html[data-theme="dark"] .main-content .fee-new {
        background: #073b27 !important;
        border-color: #166534 !important;
        color: #86efac !important;
    }

    html[data-theme="dark"] .main-content :is(.queue-count,.staff-count,.record-badge) {
        background: #26364d !important;
        color: #dce6f2 !important;
    }

    html[data-theme="dark"] .main-content :is(.badge-cash,.type-menudo,.status-active,.status-completed) {
        background: #073b27 !important;
        color: #86efac !important;
    }
    html[data-theme="dark"] .main-content :is(.badge-gcash,.status-processing,.btn-edit-custom) {
        background: #172d52 !important;
        color: #93c5fd !important;
    }
    html[data-theme="dark"] .main-content :is(.badge-maya,.role-badge) {
        background: #302553 !important;
        color: #c4b5fd !important;
    }
    html[data-theme="dark"] .main-content :is(.status-pending,.pricing-badge.menudo,.helper-warning,.difference-box) {
        background: #422b08 !important;
        color: #fcd34d !important;
        border-color: #854d0e !important;
    }
    html[data-theme="dark"] .main-content .status-awaiting-notification {
        background: #3f3507 !important;
        color: #fde68a !important;
    }
    html[data-theme="dark"] .main-content .notify-btn {
        background: #3f3507 !important;
        color: #fde68a !important;
        border-color: #854d0e !important;
    }
    html[data-theme="dark"] .main-content :is(.status-inactive,.error-box,.failed-notification-box,.btn-toggle-custom.active-btn) {
        background: #451a1a !important;
        color: #fca5a5 !important;
        border-color: #7f1d1d !important;
    }
    html[data-theme="dark"] .main-content :is(.status-claimed,.type-commercial) {
        background: #293548 !important;
        color: #cbd5e1 !important;
    }

    html[data-theme="dark"][data-accent] .main-content :is(.queue-tab.active,.tab.active) {
        background: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
        color: #fff !important;
    }

    html[data-theme="dark"] .main-content .btn-outline-secondary {
        background: transparent !important;
        border-color: #718096 !important;
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] .main-content :is(
        .cancel-btn,.btn-reset,.history-link,.history-btn,.edit-btn,
        .modal-cancel,.btn-back,.back-link,.back
    ) {
        background: #172033 !important;
        border-color: #64748b !important;
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] .profile-menu a,
    html[data-theme="dark"] .profile-menu button { color: #dce6f2 !important; }
    html[data-theme="dark"] .profile-menu a:hover,
    html[data-theme="dark"] .profile-menu button:hover { background: #243147 !important; }

    html[data-theme="dark"] :is(.topbar,.profile-menu,.profile-trigger,.top-icon-btn) {
        background-color: #172033 !important;
        border-color: #35445a !important;
        color: #e5edf7 !important;
    }

    html[data-theme="dark"] :is(.profile-menu-name,.profile-menu-role) {
        color: #dce6f2 !important;
    }

    html[data-theme="dark"] .main-content :is(.modal-close,.top-icon-btn,.profile-trigger) {
        background: #1e293b !important;
        border-color: #475569 !important;
        color: #e2e8f0 !important;
    }

    html[data-theme="dark"] .main-content .pagination-wrap .page-link {
        background: #111827 !important;
        border-color: #475569 !important;
        color: #cbd5e1 !important;
    }
    html[data-theme="dark"] .main-content .pagination-wrap .page-item.active .page-link {
        background: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
        color: #fff !important;
    }

    html[data-theme="dark"] .theme-choice,
    html[data-theme="dark"] .mode-choice { background: #111827 !important; border-color: #42516a !important; }
    html[data-theme="dark"][data-accent] :is(.theme-option,.mode-option) input:checked + :is(.theme-choice,.mode-choice) {
        background: color-mix(in srgb, var(--user-accent) 18%, #111827) !important;
        border-color: var(--user-accent) !important;
        box-shadow: 0 0 0 3px var(--user-accent-ring) !important;
    }
    html[data-theme="dark"] .appearance-section + .appearance-section,
    html[data-theme="dark"] .appearance-actions { border-color: #35445a !important; }

    /* Receipts and claim tickets remain readable paper documents in dark mode. */
    html[data-theme="dark"] .main-content .thermal-receipt,
    html[data-theme="dark"] .main-content .claim-ticket {
        background: #fff !important;
        color: #111827 !important;
        border-color: #94a3b8 !important;
    }
    html[data-theme="dark"] .main-content .thermal-receipt *,
    html[data-theme="dark"] .main-content .claim-ticket * {
        background-color: transparent !important;
        background-image: none !important;
        color: #111827 !important;
        box-shadow: none !important;
    }
    html[data-theme="dark"] .main-content .thermal-receipt :is(.line,.total-box),
    html[data-theme="dark"] .main-content .claim-ticket :is(.claim-ticket-rule,.claim-ticket-section-title,.claim-ticket-row,.claim-ticket-estimate,.claim-ticket-reminder) {
        border-color: #334155 !important;
    }
    html[data-theme="dark"] .main-content .claim-ticket .claim-ticket-rice-icon {
        color: var(--user-accent-dark) !important;
    }
    html[data-theme="dark"] .main-content .claim-ticket :is(.claim-ticket-mill,.claim-ticket-title,.claim-ticket-queue,.claim-ticket-estimate-value) {
        color: #0f172a !important;
    }
    html[data-theme="dark"] .main-content .claim-ticket :is(.claim-ticket-brand,.claim-ticket-row span:first-child,.claim-ticket-estimate-note,.claim-ticket-reminder small) {
        color: #475569 !important;
    }
    html[data-theme="dark"] .receipt-preview-area { background: #111827 !important; }

    /* Printable report paper remains white even while its surrounding screen is dark. */
    html[data-theme="dark"] .print-paper {
        background: #fff !important;
        color: #0f172a !important;
    }
    html[data-theme="dark"] .print-paper * { color: inherit; }
}

/* Shared mobile layout corrections for every owner and staff module. */
@media (max-width: 768px) {
    html, body {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }

    .main-content {
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 0 !important;
        margin-left: 0 !important;
    }

    .topbar {
        width: 100% !important;
        max-width: 100% !important;
    }

    .main-content > * {
        max-width: 100% !important;
    }

    .main-content :is(.filter-grid, .filter-row, .report-meta) {
        grid-template-columns: 1fr !important;
    }

    .main-content :is(.summary-grid, .collection-summary-grid) {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .main-content :is(.table-responsive, .table-scroll, .table-wrap) {
        max-width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }
}

@media (max-width: 480px) {
    .main-content :is(.summary-grid, .collection-summary-grid) {
        grid-template-columns: 1fr !important;
    }
}

@media print {
    html, body { background: #fff !important; color: #000 !important; color-scheme: light !important; }
}
</style>
