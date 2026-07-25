<style>
@media screen {
    html[data-accent="classic"] {
        --user-accent: #168344;
        --user-accent-dark: #125f34;
        --user-sidebar-start: #0f3d1c;
        --user-sidebar-main: #166534;
    }

    html[data-accent="forest"] {
        --user-accent: #356b3d;
        --user-accent-dark: #244c2a;
        --user-sidebar-start: #18351f;
        --user-sidebar-main: #294f2f;
    }

    html[data-accent="emerald"] {
        --user-accent: #059669;
        --user-accent-dark: #047857;
        --user-sidebar-start: #064e3b;
        --user-sidebar-main: #047857;
    }

    html[data-accent] .sidebar {
        background: linear-gradient(145deg,
            var(--user-sidebar-start) 0%,
            var(--user-sidebar-main) 25%,
            var(--user-sidebar-main) 75%,
            var(--user-sidebar-start) 100%) !important;
    }

    html[data-accent] .main-content :is(
        .btn-success, .btn-save, .save-theme,
        .quick-btn.btn-success, button.btn-success
    ) {
        background-color: var(--user-accent) !important;
        border-color: var(--user-accent) !important;
    }

    html[data-accent] .main-content :is(
        .btn-success, .btn-save, .save-theme,
        .quick-btn.btn-success, button.btn-success
    ):hover {
        background-color: var(--user-accent-dark) !important;
        border-color: var(--user-accent-dark) !important;
    }

    html[data-accent] .avatar-small { background-color: var(--user-accent-dark) !important; }

    html[data-theme="dark"] {
        color-scheme: dark;
        --page-bg: #0f172a;
        --text-dark: #edf3fb;
        --text-muted: #9fb0c5;
        --border-soft: #334155;
        --shadow-soft: 0 14px 35px rgba(0, 0, 0, .24);
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
    html[data-theme="dark"] .main-content :is(.status-inactive,.error-box,.failed-notification-box,.btn-toggle-custom.active-btn) {
        background: #451a1a !important;
        color: #fca5a5 !important;
        border-color: #7f1d1d !important;
    }
    html[data-theme="dark"] .main-content :is(.status-claimed,.type-commercial) {
        background: #293548 !important;
        color: #cbd5e1 !important;
    }

    html[data-theme="dark"] .main-content :is(.queue-tab.active,.tab.active,.nav-link.active) {
        background: #26364d !important;
        color: #dcfce7 !important;
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
    html[data-theme="dark"] .appearance-section + .appearance-section,
    html[data-theme="dark"] .appearance-actions { border-color: #35445a !important; }

    /* Receipts stay paper-white on screen and when printed. */
    html[data-theme="dark"] .thermal-receipt { background: #fff !important; color: #000 !important; }
    html[data-theme="dark"] .thermal-receipt * { color: #000 !important; }
    html[data-theme="dark"] .thermal-receipt [class$="-box"] { background: #fff !important; }
    html[data-theme="dark"] .receipt-preview-area { background: #111827 !important; }

    /* Printable report paper remains white even while its surrounding screen is dark. */
    html[data-theme="dark"] .print-paper {
        background: #fff !important;
        color: #0f172a !important;
    }
    html[data-theme="dark"] .print-paper * { color: inherit; }
}

@media print {
    html, body { background: #fff !important; color: #000 !important; color-scheme: light !important; }
}
</style>
