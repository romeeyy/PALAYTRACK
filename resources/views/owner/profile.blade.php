@extends(Auth::user()->role === 'staff' ? 'layouts.staff' : 'layouts.owner')

@section('content')
<div class="page-header mb-4" id="appearance">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your {{ Auth::user()->role }} account information.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('password_success'))
    <div class="alert alert-success mb-4">
        {{ session('password_success') }}
    </div>
@endif

<div class="profile-workspace">
    <aside class="profile-summary">
        <div class="summary-accent"></div>
        <div class="avatar" id="profileAvatar">
            @if(Auth::user()->profile_photo_path)
                <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" id="profilePhotoPreview">
            @else
                <span id="profileInitials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                <img src="" alt="Profile preview" id="profilePhotoPreview" hidden>
            @endif
        </div>

        <h3>{{ Auth::user()->name }}</h3>
        <p>{{ Auth::user()->email }}</p>

        <span class="role-badge">
            {{ ucfirst(Auth::user()->role) }}
        </span>
    </aside>

    <div class="profile-main">
    <section class="profile-section">
        <div class="card-header-custom">
            <span class="section-icon"><i data-lucide="user-round"></i></span>
            <div>
                <h3>Account Information</h3>
                <p>Update your personal account details.</p>
            </div>
        </div>

        <form method="POST" action="{{ route(Auth::user()->role === 'staff' ? 'staff.profile.update' : 'owner.profile.update') }}" enctype="multipart/form-data" class="account-form"
            data-confirm-title="Update Profile?"
            data-confirm-message="Save these profile changes?"
            data-confirm-button="Save Changes">
            @csrf

            <div class="form-group">
                <label for="profile_photo">Profile Picture</label>
                <div class="photo-controls">
                    <label for="profile_photo" class="btn-photo">
                        <i data-lucide="image-plus"></i>
                        Choose Photo
                    </label>
                    <input id="profile_photo" type="file" name="profile_photo" accept="image/jpeg,image/png,image/webp" hidden>

                    @if(Auth::user()->profile_photo_path)
                        <label class="remove-photo">
                            <input type="checkbox" name="remove_photo" value="1" id="remove_photo">
                            Remove current photo
                        </label>
                    @endif
                </div>
                <p class="field-help">JPG, PNG, or WebP. Maximum file size: 2 MB.</p>
                @error('profile_photo')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    class="@error('name') is-invalid @enderror"
                    value="{{ old('name', Auth::user()->name) }}" 
                    required
                >
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="@error('email') is-invalid @enderror"
                    value="{{ old('email', Auth::user()->email) }}" 
                    required
                >
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    Save Changes
                </button>
            </div>
        </form>
    </section>

    <div class="profile-divider"></div>

    <section class="profile-section security-card">
        <div class="card-header-custom">
            <span class="section-icon"><i data-lucide="lock-keyhole"></i></span>
            <div>
                <h3>Change Password</h3>
                <p>Use a strong password that you do not use elsewhere.</p>
            </div>
        </div>

        <form method="POST" action="{{ route(Auth::user()->role === 'staff' ? 'staff.profile.password' : 'owner.profile.password') }}"
            data-confirm-title="Change Password?"
            data-confirm-message="Save your new account password?"
            data-confirm-button="Change Password">
            @csrf

            <div class="password-grid">
                <div class="form-group full-row">
                    <label for="current_password">Current Password</label>
                    <div class="password-input-wrap"><input id="current_password" type="password" name="current_password" class="@error('current_password', 'passwordUpdate') is-invalid @enderror" autocomplete="current-password" required><button type="button" class="password-toggle" data-target="current_password" aria-label="Show current password"><i data-lucide="eye"></i></button></div>
                    @error('current_password', 'passwordUpdate')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="password-input-wrap"><input id="password" type="password" name="password" class="@error('password', 'passwordUpdate') is-invalid @enderror" autocomplete="new-password" required><button type="button" class="password-toggle" data-target="password" aria-label="Show new password"><i data-lucide="eye"></i></button></div>
                    @error('password', 'passwordUpdate')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="password-input-wrap"><input id="password_confirmation" type="password" name="password_confirmation" class="@error('password', 'passwordUpdate') is-invalid @enderror" autocomplete="new-password" required><button type="button" class="password-toggle" data-target="password_confirmation" aria-label="Show password confirmation"><i data-lucide="eye"></i></button></div>
                </div>
            </div>
            <div class="password-requirements" aria-label="Password requirements">
                <span data-rule="length">8+ characters</span><span data-rule="letter">A letter</span><span data-rule="number">A number</span><span data-rule="special">A special character</span>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Change Password</button>
            </div>
        </form>
    </section>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px !important;
}

.page-title {
    font-size: 32px;
    font-weight: 800;
    color: #071739;
    margin: 0;
}

.page-subtitle {
    color: #64748b;
    margin-top: 6px;
    font-size: 15px;
}

.alert {
    padding: 14px 18px;
    border-radius: 14px;
    font-weight: 600;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.profile-workspace {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr);
    background: #ffffff;
    border-radius: 22px;
    overflow: hidden;
    box-shadow: 0 14px 36px rgba(15, 23, 42, 0.07);
    border: 1px solid #e8edf3;
}

.profile-summary {
    position: relative;
    text-align: center;
    padding: 22px 18px 18px;
    background: linear-gradient(180deg, var(--user-accent-soft, #f5faf4) 0%, #fbfdfb 100%);
    border-right: 1px solid #e4ece3;
}

.summary-accent {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--user-accent-dark, #166534), var(--user-accent, #4d8f36));
}

.profile-main {
    padding: 18px 24px 20px;
}

.profile-section {
    max-width: 980px;
}

.avatar {
    width: 74px;
    height: 74px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--user-accent-dark, #1f5f1f), var(--user-accent, #3d8b32));
    color: #ffffff;
    font-size: 30px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 11px;
    box-shadow: 0 8px 20px rgba(31, 95, 31, 0.18);
    overflow: hidden;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-summary h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
}

.profile-summary p {
    margin: 5px 0 11px;
    color: #64748b;
}

.role-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 999px;
    background: var(--user-accent-soft, #ecfdf5);
    color: var(--user-accent-dark, #166534);
    font-weight: 700;
    font-size: 14px;
}

.card-header-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 9px;
}

.section-icon {
    display: grid;
    place-items: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border-radius: 11px;
    background: var(--user-accent-soft, #edf7eb);
    color: var(--user-accent-dark, #24723a);
}

.section-icon svg {
    width: 19px;
    height: 19px;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
}

.card-header-custom p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 14px;
}

.form-group {
    margin-bottom: 8px;
}

.account-form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 16px;
}

.account-form .form-group:first-of-type,
.account-form .form-actions {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    font-weight: 700;
    color: #334155;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    height: 38px;
    border-radius: 10px;
    border: 1px solid #dbe3ef;
    padding: 0 14px;
    font-size: 15px;
    color: #0f172a;
    outline: none;
    background: #ffffff;
}

.form-group input:focus {
    border-color: var(--user-accent, #2f7d32);
    box-shadow: 0 0 0 4px var(--user-accent-ring, rgba(47, 125, 50, 0.12));
}

.form-group input:disabled {
    background: #f1f5f9;
    color: #64748b;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 5px;
}

.profile-divider {
    height: 1px;
    margin: 12px 0;
    background: #e8edf3;
}

.photo-controls {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.btn-photo {
    min-height: 40px;
    padding: 0 15px;
    border: 1px solid #bbd7bc;
    border-radius: 11px;
    color: #166534;
    background: #f6fbf5;
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    margin: 0 !important;
    cursor: pointer;
}

.btn-photo svg { width: 17px; height: 17px; }
.remove-photo { display: inline-flex !important; align-items: center; gap: 8px; margin: 0 !important; font-weight: 600 !important; color: #64748b !important; }
.remove-photo input { width: 16px; height: 16px; }
.field-help { margin: 5px 0 0; color: #64748b; font-size: 12px; }
.field-error { margin-top: 6px; color: #b42318; font-size: 12.8px; font-weight: 700; }
.profile-main input.is-invalid { border-color:#ef4444; background:#fffafa; box-shadow:0 0 0 .16rem rgba(239,68,68,.08); }
.compact-alert { margin-bottom: 18px; }
.password-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0 14px; }
.password-grid .full-row { grid-column: auto; }
.password-input-wrap { position:relative; }
.password-input-wrap input { padding-right:46px; }
.password-input-wrap input::-ms-reveal,
.password-input-wrap input::-ms-clear,
.password-input-wrap input::-webkit-credentials-auto-fill-button { display:none !important; }
.password-toggle { position:absolute; right:9px; top:50%; transform:translateY(-50%); width:32px; height:32px; display:grid; place-items:center; border:0; border-radius:8px; background:transparent; color:#64748b; cursor:pointer; }
.password-toggle:hover { background:var(--user-accent-soft,#edf7eb); color:var(--user-accent-dark,#24723a); }
.password-toggle svg { width:18px; height:18px; }
.password-requirements { display:flex; flex-wrap:wrap; gap:8px 14px; margin-top:8px; color:#64748b; font-size:12px; font-weight:700; }
.password-requirements span::before { content:'\25CB'; margin-right:5px; color:#94a3b8; }
.password-requirements span.is-valid { color:var(--user-accent-dark,#166534); }
.password-requirements span.is-valid::before { content:'\2713'; color:var(--user-accent,#15803d); }

.btn-save {
    background: var(--user-accent, #1f7a3a);
    color: white;
    border: none;
    min-height: 40px;
    padding: 0 19px;
    border-radius: 11px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(31, 122, 58, 0.16);
}

.btn-save:hover {
    background: var(--user-accent-dark, #166534);
}

@media (max-width: 850px) {
    .profile-workspace { grid-template-columns: 1fr; }
    .profile-summary { padding: 28px 20px; border-right: 0; border-bottom: 1px solid #e4ece3; }
    .profile-main { padding: 25px 20px 28px; }
    .password-grid { grid-template-columns: 1fr; }
    .password-grid .full-row { grid-column: 1; }
    .account-form { grid-template-columns: 1fr; }
    .account-form .form-group:first-of-type,
    .account-form .form-actions { grid-column: 1; }
    .form-actions { justify-content: stretch; }
    .form-actions .btn-save { width: 100%; }
    .password-requirements { gap: 7px 10px; }
}
</style>
@include('partials.field-validation-focus')

<script>
document.getElementById('profile_photo')?.addEventListener('change', function () {
    const file = this.files?.[0];
    if (!file) return;

    const preview = document.getElementById('profilePhotoPreview');
    const initials = document.getElementById('profileInitials');
    preview.src = URL.createObjectURL(file);
    preview.hidden = false;
    if (initials) initials.hidden = true;

    const removePhoto = document.getElementById('remove_photo');
    if (removePhoto) removePhoto.checked = false;
});

document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', () => {
        const input = document.getElementById(toggle.dataset.target);
        if (!input) return;
        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        toggle.setAttribute('aria-label', `${visible ? 'Show' : 'Hide'} ${toggle.dataset.target.replaceAll('_', ' ')}`);
        toggle.innerHTML = `<i data-lucide="${visible ? 'eye' : 'eye-off'}"></i>`;
        window.lucide?.createIcons();
    });
});

const newPassword = document.getElementById('password');
const requirementRules = {
    length: value => value.length >= 8,
    letter: value => /[A-Za-z]/.test(value),
    number: value => /\d/.test(value),
    special: value => /[^A-Za-z0-9]/.test(value)
};
const updatePasswordRequirements = () => {
    const value = newPassword?.value || '';
    document.querySelectorAll('.password-requirements [data-rule]').forEach(item => {
        item.classList.toggle('is-valid', requirementRules[item.dataset.rule]?.(value));
    });
};
newPassword?.addEventListener('input', updatePasswordRequirements);
updatePasswordRequirements();
</script>
@endsection
