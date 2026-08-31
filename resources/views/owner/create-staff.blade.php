@extends('layouts.owner')

@section('content')
<style>
    .page-shell {
        max-width: 820px;
        margin: 0 auto;
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
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .staff-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #eef2f7;
        background: #f8fbf8;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .staff-card-title {
        margin: 0 0 4px;
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .staff-card-text {
        margin: 0;
        color: #64748b;
        font-size: 0.84rem;
    }

    .header-icon { width:40px; height:40px; border-radius:11px; display:grid; place-items:center; flex:0 0 40px; color:#15803d; background:#eaf7eb; }
    .header-icon i { width:20px; height:20px; }

    .staff-card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label-custom {
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 7px;
        font-size: 0.9rem;
    }

    .form-control-custom {
        border-radius: 11px;
        min-height: 46px;
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

    .input-error {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08) !important;
    }

    .input-success {
        border-color: #16a34a !important;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.08) !important;
    }

    .password-note {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 6px;
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
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .form-control-custom.is-invalid { border-color:#ef4444; background:#fffafa; box-shadow:0 0 0 .16rem rgba(239,68,68,.08); }
    .invalid-feedback { display:block; margin-top:6px; color:#b42318; font-size:.8rem; font-weight:600; }

    .access-note {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
        padding: 10px 12px;
        border: 1px solid #dcebdc;
        border-radius: 10px;
        background: #f7fbf7;
        color: #475569;
        font-size: .8rem;
    }

    .access-note i { width:17px; height:17px; color:#15803d; flex:0 0 auto; }
    .access-note strong { color:#166534; }

    .show-passwords {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
    }

    .show-passwords input { width:16px; height:16px; accent-color:#15803d; }

    .submit-btn {
        border: none;
        border-radius: 11px;
        background: #15803d;
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
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.18);
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
</style>
@include('partials.form-dark-mode')
@include('partials.field-validation-focus')

<div class="page-shell">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create Staff Account</h1>
            <p class="page-subtitle">Add a new staff account for daily system access.</p>
        </div>
        <a href="{{ route('owner.staff-accounts') }}" class="back-btn">
            <i data-lucide="arrow-left"></i>
            <span>Back to Staff Accounts</span>
        </a>
    </div>

    <div class="staff-card">
        <div class="staff-card-header">
            <div class="header-icon"><i data-lucide="user-plus"></i></div>
            <div>
                <h5 class="staff-card-title">Staff Information</h5>
                <p class="staff-card-text">Enter the account details and secure login credentials.</p>
            </div>
        </div>

        <div class="staff-card-body">
            <form id="staffForm" method="POST" action="{{ route('owner.staff-accounts.store') }}">
                @csrf

                <div class="access-note">
                    <i data-lucide="shield-check"></i>
                    <span>This account will be created as <strong>Staff</strong> with <strong>Active</strong> access.</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label form-label-custom">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control form-control-custom @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
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
                                value="{{ old('email') }}"
                                placeholder="Enter email address"
                                required
                            >
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label form-label-custom">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                placeholder="Enter password"
                                required
                            >
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="passwordLengthWarning" class="password-warning">
                                Use at least 8 characters with a letter and a number.
                            </div>
                            <div id="passwordLengthSuccess" class="password-success">
                                Password format is valid.
                            </div>
                            <div class="password-note">Use at least 8 characters with letters and numbers.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label form-label-custom">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control form-control-custom @error('password') is-invalid @enderror"
                                placeholder="Re-enter password"
                                required
                            >
                            <div id="passwordMatchWarning" class="password-warning">
                                Passwords do not match.
                            </div>
                            <div id="passwordMatchSuccess" class="password-success">
                                Passwords match.
                            </div>
                        </div>
                    </div>
                </div>

                <label class="show-passwords">
                    <input type="checkbox" id="showPasswords">
                    <span>Show passwords</span>
                </label>

                <div class="form-actions">
                    <a href="{{ route('owner.staff-accounts') }}" class="cancel-btn">Cancel</a>
                    <button type="submit" id="submitBtn" class="submit-btn">
                        Create Staff Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="toastMessage" class="toast-message">
    Creating staff account...
</div>

<script>
    const staffForm = document.getElementById('staffForm');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');
    const toastMessage = document.getElementById('toastMessage');
    const showPasswords = document.getElementById('showPasswords');

    const passwordLengthWarning = document.getElementById('passwordLengthWarning');
    const passwordLengthSuccess = document.getElementById('passwordLengthSuccess');
    const passwordMatchWarning = document.getElementById('passwordMatchWarning');
    const passwordMatchSuccess = document.getElementById('passwordMatchSuccess');

    function validatePasswordFields() {
        const passwordValue = password.value;
        const confirmValue = confirmPassword.value;

        const isLengthValid = passwordValue.length >= 8 && /[A-Za-z]/.test(passwordValue) && /\d/.test(passwordValue);
        const isMatch = passwordValue === confirmValue && confirmValue.length > 0;

        password.classList.remove('input-error', 'input-success');
        confirmPassword.classList.remove('input-error', 'input-success');

        passwordLengthWarning.style.display = 'none';
        passwordLengthSuccess.style.display = 'none';
        passwordMatchWarning.style.display = 'none';
        passwordMatchSuccess.style.display = 'none';

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

        submitBtn.disabled = passwordValue.length > 0 && confirmValue.length > 0
            ? !(isLengthValid && isMatch)
            : false;

        return isLengthValid && isMatch;
    }

    password.addEventListener('input', validatePasswordFields);
    confirmPassword.addEventListener('input', validatePasswordFields);
    showPasswords.addEventListener('change', function () {
        const inputType = this.checked ? 'text' : 'password';
        password.type = inputType;
        confirmPassword.type = inputType;
    });

    staffForm.addEventListener('submit', function(event) {
        if (!validatePasswordFields()) {
            event.preventDefault();
            return;
        }

        toastMessage.classList.add('show');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';
    });
    function capitalizeWords(input) {
    let words = input.value.toLowerCase().split(" ");
    for (let i = 0; i < words.length; i++) {
        if (words[i]) {
            words[i] = words[i][0].toUpperCase() + words[i].substring(1);
        }
    }
    input.value = words.join(" ");
}
</script>
@endsection
