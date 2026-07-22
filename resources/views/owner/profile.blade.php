@extends('layouts.owner')

@section('content')
<div class="page-header mb-4">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your owner account information.</p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        {{ $errors->first() }}
    </div>
@endif

<div class="profile-grid">
    <div class="profile-card profile-summary">
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>

        <h3>{{ Auth::user()->name }}</h3>
        <p>{{ Auth::user()->email }}</p>

        <span class="role-badge">
            {{ ucfirst(Auth::user()->role) }}
        </span>
    </div>

    <div class="profile-card">
        <div class="card-header-custom">
            <div>
                <h3>Account Information</h3>
                <p>Update your personal account details.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('owner.profile.update') }}"
            data-confirm-title="Update Profile?"
            data-confirm-message="Save these owner profile changes?"
            data-confirm-button="Save Changes">
            @csrf

            <div class="form-group">
                <label>Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', Auth::user()->name) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', Auth::user()->email) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Role</label>
                <input 
                    type="text" 
                    value="{{ ucfirst(Auth::user()->role) }}" 
                    disabled
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.profile-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 24px;
}

.profile-card {
    background: #ffffff;
    border-radius: 22px;
    padding: 28px;
    box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
    border: 1px solid #eef2f7;
}

.profile-summary {
    text-align: center;
    height: fit-content;
}

.avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1f5f1f, #3d8b32);
    color: #ffffff;
    font-size: 30px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    box-shadow: 0 12px 25px rgba(31, 95, 31, 0.25);
}

.profile-summary h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
}

.profile-summary p {
    margin: 8px 0 16px;
    color: #64748b;
}

.role-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #166534;
    font-weight: 700;
    font-size: 14px;
}

.card-header-custom {
    margin-bottom: 24px;
}

.card-header-custom h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
}

.card-header-custom p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}

.form-group input {
    width: 100%;
    height: 48px;
    border-radius: 12px;
    border: 1px solid #dbe3ef;
    padding: 0 14px;
    font-size: 15px;
    color: #0f172a;
    outline: none;
    background: #ffffff;
}

.form-group input:focus {
    border-color: #2f7d32;
    box-shadow: 0 0 0 4px rgba(47, 125, 50, 0.12);
}

.form-group input:disabled {
    background: #f1f5f9;
    color: #64748b;
}

.form-actions {
    margin-top: 8px;
}

.btn-save {
    background: #1f7a3a;
    color: white;
    border: none;
    padding: 13px 22px;
    border-radius: 12px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 10px 20px rgba(31, 122, 58, 0.22);
}

.btn-save:hover {
    background: #166534;
}

@media (max-width: 900px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
