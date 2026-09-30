<style>
    .audit-page-header{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:10px}
    .audit-page-title{margin:0 0 3px;color:#0f172a;font-size:1.6rem;font-weight:900;line-height:1.15}
    .audit-page-subtitle{margin:0;color:#64748b;font-size:.9rem}
    .audit-back{display:inline-flex;align-items:center;gap:7px;flex:0 0 auto;min-height:36px;padding:6px 11px;border:1px solid color-mix(in srgb,var(--user-accent,#168344) 28%,#cbd5e1);border-radius:10px;background:#fff;color:var(--user-accent-dark,#334155);font-size:.84rem;font-weight:800;text-decoration:none;transition:.2s ease}
    .audit-back svg{width:17px;height:17px}
    .audit-back:hover{border-color:var(--user-accent,#168344);background:var(--user-accent-soft,#f8fafc);color:var(--user-accent-dark,#2f5d1e);text-decoration:none}
    .audit-card{overflow:hidden;border:1px solid #b9d2bd;border-radius:18px;background:linear-gradient(135deg,#f9fdf9 0%,#eaf5ec 100%);box-shadow:0 14px 30px rgba(24,65,35,.10)}
    .audit-card-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 18px;border-bottom:1px solid #cbdccf;background:linear-gradient(135deg,#fff 0%,#edf7ef 100%)}
    .audit-card-header h2{margin:0 0 3px;color:#0f172a;font-size:1.08rem;font-weight:900}
    .audit-card-header p{margin:0;color:#64748b;font-size:.84rem}
    .audit-count{padding:7px 11px;border:1px solid color-mix(in srgb,var(--user-accent,#168344) 24%,#dbe5df);border-radius:999px;background:var(--user-accent-soft,#f8fbf8);color:var(--user-accent-dark,#166534);font-size:.78rem;font-weight:800;white-space:nowrap}
    .audit-filter{display:flex;align-items:flex-end;justify-content:space-between;gap:14px;padding:13px 18px;border-bottom:1px solid #cbdccf;background:#eef7f0}
    .audit-filter-group{display:flex;flex-direction:column;gap:7px}
    .audit-filter-label{color:#334155;font-size:.82rem;font-weight:900}
    .audit-filter-select{min-width:230px;min-height:40px;padding:7px 12px;border:1px solid #cbd5e1;border-radius:11px;background:#fff;color:#334155;font-weight:700}
    .audit-filter-actions{display:flex;gap:9px}
    .audit-button{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:9px 15px;border-radius:11px;font-size:.88rem;font-weight:800;text-decoration:none}
    .audit-button-primary{border:1px solid var(--user-accent,#2f5d1e);background:var(--user-accent,#2f5d1e);color:#fff}
    .audit-button-secondary{border:1px solid #cbd5e1;background:#fff;color:#475569}
    .audit-table-scroll{overflow-x:auto}
    .audit-table{width:100%;margin:0;border-collapse:collapse}
    .audit-table th{padding:13px 16px;border-bottom:1px solid #cbdccf;background:#edf5ef;color:#244c2d;font-size:.76rem;font-weight:800;letter-spacing:.02em;text-align:left;text-transform:uppercase;white-space:nowrap}
    .audit-table td{padding:13px 16px;border-bottom:1px solid #dce9df;background:rgba(255,255,255,.42);color:#334155;font-size:.86rem;vertical-align:middle}
    .audit-table tbody tr:last-child td{border-bottom:0}
    .audit-table tbody tr:hover{background:#fbfdfb}
    .audit-table tbody tr:nth-child(even) td{background:rgba(245,251,246,.72)}
    .audit-pill{display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;font-size:.74rem;font-weight:900;white-space:nowrap}
    .audit-pill-type,.audit-pill-action{background:#eff6ff;color:#1d4ed8}
    .audit-pill-created{background:#ecfdf5;color:#047857}
    .audit-pill-updated{background:#eff6ff;color:#1d4ed8}
    .audit-pill-status{background:#fff7ed;color:#c2410c}
    .audit-pill-old{border-radius:9px;background:#fef2f2;color:#b91c1c}
    .audit-pill-new{border-radius:9px;background:#ecfdf5;color:#047857}
    .audit-value{font-weight:800;text-transform:capitalize}
    .audit-muted{color:#64748b;font-size:.84rem;font-weight:700;white-space:nowrap}
    .audit-empty{padding:36px 18px!important;color:#64748b!important;font-weight:700;text-align:center}
    .audit-footer{display:flex;align-items:center;justify-content:space-between;gap:12px;min-height:58px;padding:12px 16px;border-top:1px solid #e2e8f0;background:#fbfcfd}
    .audit-footer-text{margin:0;color:#64748b;font-size:.84rem;font-weight:700}
    .audit-pagination-actions{display:flex;gap:7px}
    .audit-page-link{display:inline-flex;align-items:center;min-height:36px;padding:7px 11px;border:1px solid #cbd5e1;border-radius:9px;background:#fff;color:#334155;font-size:.82rem;font-weight:800;text-decoration:none}
    .audit-page-link.disabled{opacity:.45;pointer-events:none}
    html[data-theme="dark"] .audit-page-title{color:#f8fafc}
    html[data-theme="dark"] :is(.audit-page-subtitle,.audit-muted,.audit-footer-text){color:#9fb0c7}
    html[data-theme="dark"] .audit-card{border-color:#35445a;background:#182236;box-shadow:none}
    html[data-theme="dark"] :is(.audit-filter,.audit-table th){border-color:#35445a;background:#202c40}
    html[data-theme="dark"] :is(.audit-filter-label,.audit-table th){color:#edf3fb}
    html[data-theme="dark"] :is(.audit-table td,.audit-footer){border-color:#35445a;color:#dce6f2}
    html[data-theme="dark"] :is(.audit-back,.audit-button-secondary,.audit-page-link,.audit-filter-select){border-color:#42516a;background:#111827;color:#dce6f2}
    html[data-theme="dark"] .audit-table tbody tr:hover{background:#1d293b}
    @media(max-width:768px){
        .audit-page-header,.audit-filter,.audit-footer,.audit-card-header{align-items:stretch;flex-direction:column}
        .audit-back{align-self:flex-start}
        .audit-filter-group,.audit-filter-select,.audit-filter-actions,.audit-filter-actions .audit-button{width:100%}
        .audit-page-title{font-size:1.65rem}
    }
</style>
