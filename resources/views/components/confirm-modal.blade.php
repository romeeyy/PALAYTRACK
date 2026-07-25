<style>
    .system-confirm-overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;padding:16px;z-index:10000}
    .system-confirm-overlay.show{display:flex}
    .system-confirm-box{width:100%;max-width:390px;background:#fff;border-radius:14px;padding:22px;box-shadow:0 22px 55px rgba(15,23,42,.22)}
    .system-confirm-title{font-size:1.2rem;font-weight:900;color:#0f172a;margin:0 0 8px}
    .system-confirm-message{font-size:.9rem;color:#64748b;line-height:1.5;margin:0 0 20px}
    .system-confirm-actions{display:flex;justify-content:flex-end;gap:9px}
    .system-confirm-cancel,.system-confirm-submit{border-radius:9px;padding:8px 13px;font-size:.85rem;font-weight:800}
    .system-confirm-cancel{background:#fff;border:1px solid #d1d5db;color:#111827}
    .system-confirm-submit{border:0;background:#15803d;color:#fff}
    .system-confirm-submit.danger{background:#b42318}
    .system-confirm-submit:disabled{opacity:.65;cursor:not-allowed}
    html[data-theme="dark"] .system-confirm-overlay{background:rgba(2,6,23,.72)}
    html[data-theme="dark"] .system-confirm-box{background:#172033;border:1px solid #35445a;box-shadow:0 22px 55px rgba(0,0,0,.4)}
    html[data-theme="dark"] .system-confirm-title{color:#f1f5f9}
    html[data-theme="dark"] .system-confirm-message{color:#aebdd0}
    html[data-theme="dark"] .system-confirm-cancel{background:#111827;border-color:#52637b;color:#e2e8f0}
</style>

<div id="systemConfirmModal" class="system-confirm-overlay" aria-hidden="true">
    <div class="system-confirm-box" role="dialog" aria-modal="true" aria-labelledby="systemConfirmTitle">
        <h2 id="systemConfirmTitle" class="system-confirm-title">Confirm Action</h2>
        <p id="systemConfirmMessage" class="system-confirm-message">Are you sure you want to continue?</p>
        <div class="system-confirm-actions">
            <button type="button" id="systemConfirmCancel" class="system-confirm-cancel">Cancel</button>
            <button type="button" id="systemConfirmSubmit" class="system-confirm-submit">Confirm</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('systemConfirmModal');
    const title = document.getElementById('systemConfirmTitle');
    const message = document.getElementById('systemConfirmMessage');
    const cancel = document.getElementById('systemConfirmCancel');
    const submit = document.getElementById('systemConfirmSubmit');
    let pendingForm = null;

    function closeSystemConfirm() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        pendingForm = null;
    }

    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!form.dataset.confirmMessage || form.dataset.confirmed === 'true') return;
        event.preventDefault();
        pendingForm = form;
        title.textContent = form.dataset.confirmTitle || 'Confirm Action';
        message.textContent = form.dataset.confirmMessage;
        submit.textContent = form.dataset.confirmButton || 'Confirm';
        submit.classList.toggle('danger', form.dataset.confirmVariant === 'danger');
        submit.disabled = false;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        cancel.focus();
    });

    cancel.addEventListener('click', closeSystemConfirm);
    submit.addEventListener('click', function () {
        if (!pendingForm) return;
        submit.disabled = true;
        pendingForm.dataset.confirmed = 'true';
        pendingForm.querySelectorAll('button[type="submit"]').forEach(button => button.disabled = true);
        pendingForm.submit();
    });
    modal.addEventListener('click', event => { if (event.target === modal) closeSystemConfirm(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape' && modal.classList.contains('show')) closeSystemConfirm(); });
});
</script>
