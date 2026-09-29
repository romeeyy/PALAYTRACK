@extends('layouts.owner')

@section('content')
<style>
    .page-shell {
        max-width: 1100px;
        margin: 18px auto 24px;
        padding: 24px;
        border: 1px solid #edf2f7;
        border-radius: 24px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fbf7 100%);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.9rem;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 5px;
    }

    .page-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 11px;
        background: #fff;
        color: #334155;
        font-size: .88rem;
        font-weight: 800;
        text-decoration: none;
    }

    .back-btn:hover { border-color:#2f5d1e; background:#f8fafc; color:#2f5d1e; }

    .staff-card {
        background: #ffffff;
        border: 1px solid #dfe8de;
        border-top: 4px solid #2f7d32;
        border-radius: 22px;
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.07);
        overflow: hidden;
    }

    .staff-card-header {
        padding: 22px 26px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(135deg, #f3faf1 0%, #ffffff 72%);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .staff-card-title {
        margin: 0 0 4px;
        font-size: 1.15rem;
        font-weight: 900;
        color: #0f172a;
    }

    .staff-card-text {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .header-icon { width:46px; height:46px; border-radius:14px; display:grid; place-items:center; flex:0 0 46px; color:#fff; background:linear-gradient(145deg,#15803d,#2f6b24); box-shadow:0 8px 18px rgba(21,128,61,.2); }
    .header-icon i { width:22px; height:22px; }

    .staff-card-body {
        padding: 26px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label-custom {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 7px;
        font-size: 0.9rem;
    }

    .form-control-custom {
        border-radius: 11px;
        min-height: 48px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        padding: 11px 13px;
        font-size: 0.92rem;
        color: #111827;
        box-shadow: none;
        transition: 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #2f5d1e;
        box-shadow: 0 0 0 3px rgba(47, 93, 30, 0.10);
    }

    .form-control-custom.is-invalid { border-color:#ef4444; background:#fffafa; box-shadow:0 0 0 .16rem rgba(239,68,68,.08); }
    .invalid-feedback { display:block; margin-top:6px; color:#b42318; font-size:.8rem; font-weight:600; }

    .security-box {
        margin-top: 6px;
        background: #f7faf7;
        border: 1px solid #dfe8de;
        border-radius: 16px;
        padding: 18px 18px 0;
    }

    .security-title {
        font-size: 1rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 14px;
    }

    .input-hint {
        font-size: 0.82rem;
        color: #64748b;
        margin-top: 6px;
    }

    .account-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 9px;
        border-radius: 999px;
        background: {{ $staff->is_active ? '#edf8f0' : '#f1f5f9' }};
        color: {{ $staff->is_active ? '#187340' : '#64748b' }};
        font-size: .72rem;
        font-weight: 800;
    }
    .account-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    .password-field { position: relative; }
    .password-field .form-control-custom { padding-right: 46px; }
    .password-field .form-control-custom::-ms-reveal,
    .password-field .form-control-custom::-ms-clear,
    .password-field .form-control-custom::-webkit-credentials-auto-fill-button { display:none !important; }
    .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: grid; place-items: center; width: 28px; height: 28px; padding: 0; border: 0; background: transparent; color: #64748b; cursor: pointer; }
    .password-toggle:hover { color: #166534; }
    .password-toggle i { width: 18px; height: 18px; }
    .password-checklist { display: flex; flex-wrap: nowrap; gap: 5px 10px; margin-top: 7px; color: #64748b; font-size: .72rem; white-space: nowrap; }
    .password-check { display: inline-flex; align-items: center; gap: 5px; }
    .password-check::before { content: '○'; font-size: .9rem; color: #94a3b8; }
    .password-check.valid { color: #15803d; }
    .password-check.valid::before { content: '✓'; font-weight: 800; color: #15803d; }

    .input-error {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08) !important;
    }

    .input-success {
        border-color: #16a34a !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.08) !important;
    }

    .password-warning {
        font-size: 0.82rem;
        color: #dc2626;
        margin-top: 6px;
        display: none;
        font-weight: 700;
    }

    .password-success {
        font-size: 0.82rem;
        color: #15803d;
        margin-top: 6px;
        display: none;
        font-weight: 700;
    }

    .error-box {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 12px 14px;
        border-radius: 11px;
        margin-bottom: 16px;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
        flex-wrap: wrap;
    }

    .submit-btn {
        border: none;
        border-radius: 11px;
        background: var(--user-accent, #168344);
        color: #fff;
        font-weight: 800;
        padding: 11px 18px;
        font-size: 0.9rem;
    }

    .submit-btn:hover {
        background: #166534;
    }

    .submit-btn:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    .cancel-btn {
        border: 1px solid #d1d5db;
        border-radius: 11px;
        background: #ffffff;
        color: #111827;
        font-weight: 800;
        padding: 11px 18px;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .cancel-btn:hover {
        background: #f8fafc;
        color: #111827;
    }

    .toast-message {
        position: fixed;
        top: 24px;
        right: 24px;
        background: #15803d;
        color: #ffffff;
        padding: 13px 18px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 800;
        box-shadow: 0 12px 28px var(--user-accent-ring, rgba(15, 23, 42, 0.18));
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: 0.25s ease;
        z-index: 9999;
    }

    .toast-message.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    @media (max-width: 768px) {
        .page-shell {
            max-width: 100%;
            margin: 12px 0 18px;
            padding: 16px;
            border-radius: 20px;
        }

        .page-title {
            font-size: 1.55rem;
        }

        .staff-card-header,
        .staff-card-body {
            padding: 18px;
        }

        .form-actions {
            justify-content: stretch;
        }

        .submit-btn,
        .cancel-btn {
            width: 100%;
            text-align: center;
        }

        .toast-message {
            left: 16px;
            right: 16px;
            top: 18px;
            text-align: center;
        }

        .password-checklist { flex-wrap: wrap; white-space: normal; }
    }
</style>
@include('partials.form-dark-mode')
@include('partials.field-validation-focus')

<div class="page-shell">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Staff Account</h1>
            <p class="page-subtitle">Update staff information and account credentials when needed.</p>
        </div>
        <a href="{{ route('owner.staff-accounts') }}" class="back-btn">
            <i data-lucide="arrow-left"></i>
            <span>Back to Staff Accounts</span>
        </a>
    </div>

    <div class="staff-card">
        <div class="staff-card-header">
            <div class="header-icon"><i data-lucide="user-cog"></i></div>
            <div>
                <h5 class="staff-card-title">{{ $staff->name }}</h5>
                <span class="account-status">{{ $staff->is_active ? 'Active' : 'Inactive' }}</span>
                <p class="staff-card-text">Update account information or replace the login password.</p>
            </div>
        </div>

        <div class="staff-card-body">
            <form id="staffForm" method="POST" action="{{ route('owner.staff-accounts.update', $staff->id) }}"
                data-confirm-title="Update staff account?"
                data-confirm-message="Are you sure you want to save these staff account changes?"
                data-confirm-button="Yes, Update Account">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label form-label-custom">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control form-control-custom @error('name') is-invalid @enderror"
                                value="{{ old('name', $staff->name) }}"
                                placeholder="Enter full name"
                                oninput="capitalizeWords(this)"
                                required
                            >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label form-label-custom">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control form-control-custom @error('email') is-invalid @enderror"
                                value="{{ old('email', $staff->email) }}"
                                placeholder="Enter email address"
                                required
                            >
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="security-box">
                    <div class="security-title">Security Settings</div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label form-label-custom">New Password</label>
                                <div class="password-field">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control form-control-custom @error('password') is-invalid @enderror"
                                        placeholder="Leave blank if unchanged"
                                    >
                                    <button type="button" class="password-toggle" data-target="password" aria-label="Show password"><i data-lucide="eye"></i></button>
                                </div>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="passwordLengthWarning" class="password-warning">
                                    Use at least 8 characters with a letter and a number.
                                </div>
                                <div id="passwordLengthSuccess" class="password-success">
                                    Password format is valid.
                                </div>
                                <div class="password-checklist" aria-label="Password requirements">
                                    <span class="password-check" data-rule="length">8+ characters</span>
                                    <span class="password-check" data-rule="letter">A letter</span>
                                    <span class="password-check" data-rule="number">A number</span>
                                    <span class="password-check" data-rule="special">A special character</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label form-label-custom">Confirm New Password</label>
                                <div class="password-field">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control form-control-custom @error('password') is-invalid @enderror"
                                        placeholder="Re-enter new password"
                                    >
                                    <button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Show password"><i data-lucide="eye"></i></button>
                                </div>
                                <div id="passwordMatchWarning" class="password-warning">
                                    Passwords do not match.
                                </div>
                                <div id="passwordMatchSuccess" class="password-success">
                                    Passwords match.
                                </div>
                                <div class="input-hint">Passwords must match.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('owner.staff-accounts') }}" class="cancel-btn">Cancel</a>
                    <button type="submit" id="submitBtn" class="submit-btn">
                        Update Staff Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toastMessage" class="toast-message">
    Updating staff account...
</div>

<script>
    const staffForm = document.getElementById('staffForm');
    const nameInput = document.getElementById('name');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const toastMessage = document.getElementById('toastMessage');

    const passwordLengthWarning = document.getElementById('passwordLengthWarning');
    const passwordLengthSuccess = document.getElementById('passwordLengthSuccess');
    const passwordMatchWarning = document.getElementById('passwordMatchWarning');
    const passwordMatchSuccess = document.getElementById('passwordMatchSuccess');

    function capitalizeWords(input) {
        let words = input.value.toLowerCase().split(" ");

        for (let i = 0; i < words.length; i++) {
            if (words[i]) {
                words[i] = words[i][0].toUpperCase() + words[i].substring(1);
            }
        }

        input.value = words.join(" ");
    }

    function validatePasswordFields() {
        const passwordValue = password.value;
        const confirmValue = confirmPassword.value;

        const passwordIsEmpty = passwordValue.length === 0 && confirmValue.length === 0;
        const hasSpecialCharacter = /[^A-Za-z0-9]/.test(passwordValue);
        const isLengthValid = passwordValue.length >= 8 && /[A-Za-z]/.test(passwordValue) && /\d/.test(passwordValue) && hasSpecialCharacter;
        document.querySelector('[data-rule="length"]').classList.toggle('valid', passwordValue.length >= 8);
        document.querySelector('[data-rule="letter"]').classList.toggle('valid', /[A-Za-z]/.test(passwordValue));
        document.querySelector('[data-rule="number"]').classList.toggle('valid', /\d/.test(passwordValue));
        document.querySelector('[data-rule="special"]').classList.toggle('valid', hasSpecialCharacter);
        const isMatch = passwordValue === confirmValue && confirmValue.length > 0;

        password.classList.remove('input-error', 'input-success');
        confirmPassword.classList.remove('input-error', 'input-success');

        passwordLengthWarning.style.display = 'none';
        passwordLengthSuccess.style.display = 'none';
        passwordMatchWarning.style.display = 'none';
        passwordMatchSuccess.style.display = 'none';

        if (passwordIsEmpty) {
            submitBtn.disabled = false;
            return true;
        }

        if (passwordValue.length > 0) {
            if (isLengthValid) {
                password.classList.add('input-success');
                passwordLengthSuccess.style.display = 'block';
            } else {
                password.classList.add('input-error');
                passwordLengthWarning.style.display = 'block';
            }
        }

        if (confirmValue.length > 0) {
            if (isMatch) {
                confirmPassword.classList.add('input-success');
                passwordMatchSuccess.style.display = 'block';
            } else {
                confirmPassword.classList.add('input-error');
                passwordMatchWarning.style.display = 'block';
            }
        }

        submitBtn.disabled = !(isLengthValid && isMatch);
        return isLengthValid && isMatch;
    }

    nameInput.addEventListener('input', function () {
        capitalizeWords(this);
    });

    password.addEventListener('input', validatePasswordFields);
    confirmPassword.addEventListener('input', validatePasswordFields);

    document.querySelectorAll('.password-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const showing = password.type === 'text';
            const inputType = showing ? 'password' : 'text';
            password.type = inputType;
            confirmPassword.type = inputType;
            document.querySelectorAll('.password-toggle').forEach(function (button) {
                button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                button.innerHTML = `<i data-lucide="${showing ? 'eye' : 'eye-off'}"></i>`;
            });
            if (window.lucide) lucide.createIcons();
        });
    });

    staffForm.addEventListener('submit', function(event) {
        // Wait for the shared confirmation modal before showing progress feedback.
        if (staffForm.dataset.confirmed !== 'true') {
            return;
        }

        if (!validatePasswordFields()) {
            event.preventDefault();
            return;
        }

        toastMessage.classList.add('show');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';
    });
</script>
@endsection
