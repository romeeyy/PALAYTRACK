@extends('layouts.app')

@section('content')
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    min-height: 100vh;
    background: url("/images/login-bg.png") center/cover no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
}

.login-shell {
    width: min(880px, 90vw); /* smaller width */
    min-height: 500px;       /* slightly shorter */
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-radius: 30px;
    overflow: hidden;
    border: 2px solid rgba(255,255,255,0.72);
    box-shadow: 0 28px 80px rgba(0,0,0,0.28);
}

.login-left {
    padding: 44px 40px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background:
        linear-gradient(rgba(12, 73, 48, 0.56), rgba(12, 73, 48, 0.56)),
        url("/images/rice-field.png") center/cover no-repeat;
}

.logo-box {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    background: rgba(56,105,39,0.65);
    border: 1px solid rgba(255,255,255,0.22);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 28px;
}

.logo-box svg { width: 38px; height: 38px; }

.brand-name {
    font-size: 2.7rem;
    font-weight: 800;
    margin: 0 0 16px;
}

.brand-text {
    max-width: 420px;
    margin: 0;
    font-size: 1rem;
    line-height: 1.8;
    color: rgba(255,255,255,0.94);
}

.feature-box {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 20px;
    border-radius: 18px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.26);
    backdrop-filter: blur(8px);
}

.feature-icon {
    width: 56px;
    height: 56px;
    min-width: 56px;
    border-radius: 50%;
    background: rgba(47,93,30,0.78);
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-icon svg { width: 28px; height: 28px; }

.feature-title {
    font-weight: 800;
    margin-bottom: 6px;
}

.feature-text {
    margin: 0;
    font-size: 0.88rem;
    line-height: 1.5;
    color: rgba(255,255,255,0.9);
}

.login-right {
    padding: 44px 40px 30px;
    background: rgba(255,255,255,0.34);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-left: 2px solid rgba(255,255,255,0.65);
}

.form-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #111827;
    margin: 0 0 10px;
}

.form-subtitle {
    margin: 0 0 34px;
    color: #1f2937;
}

.form-label {
    display: block;
    font-weight: 800;
    color: #111827;
    margin-bottom: 10px;
}

.input-wrap {
    position: relative;
    margin-bottom: 24px;
}

.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #374151;
    display: flex;
}

.input-icon svg,
.password-toggle svg {
    width: 20px;
    height: 20px;
}

.custom-input {
    width: 100%;
    height: 56px;
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,0.55);
    background: rgba(255,255,255,0.64);
    padding: 0 52px;
    color: #111827;
    font-size: 1rem;
    outline: none;
}

.custom-input:focus {
    background: rgba(255,255,255,0.8);
    border-color: #2f5d1e;
    box-shadow: 0 0 0 4px rgba(47,93,30,0.13);
}

input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}

.password-toggle {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #374151;
    cursor: pointer;
    display: flex;
    padding: 0;
}

.login-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2px 0 24px;
    font-size: 0.95rem;
}

.remember-wrap {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #111827;
}

.remember-wrap input {
    width: 18px;
    height: 18px;
    accent-color: #2f5d1e;
}

.forgot-link {
    color: #2f5d1e;
    font-weight: 700;
    text-decoration: none;
}

.btn-login {
    width: 100%;
    height: 56px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #2f5d1e, #3f7a28);
    color: #fff;
    font-size: 1.05rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 12px 26px rgba(47,93,30,0.3);
}

.notice {
    margin-top: 24px;
    padding: 16px 18px;
    border-radius: 14px;
    background: rgba(255,255,255,0.45);
    border: 1px solid rgba(255,255,255,0.45);
    color: #1f2937;
    line-height: 1.6;
    font-size: 0.93rem;
}

.notice strong { color: #111827; }

.footer {
    margin-top: 24px;
    text-align: center;
    color: #374151;
    font-size: 0.9rem;
}

.error-box {
    margin-bottom: 16px;
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(254,242,242,0.9);
    color: #b91c1c;
}

@media (max-width: 900px) {
    .login-shell {
        grid-template-columns: 1fr;
        width: min(500px, 94vw);
    }

    .login-left,
    .login-right {
        padding: 38px 28px;
    }

    .login-right {
        border-left: none;
        border-top: 2px solid rgba(255,255,255,0.65);
    }
}
</style>

<div class="login-page">
    <div class="login-shell">

        <section class="login-left">
            <div>
                <div class="logo-box">
                    <svg viewBox="0 0 64 64" fill="none">
                        <path d="M32 54V14" stroke="#F6C343" stroke-width="5" stroke-linecap="round"/>
                        <path d="M32 20C24 18 18 22 16 30C24 32 30 28 32 20Z" fill="#7BC043"/>
                        <path d="M32 30C24 28 18 32 16 40C24 42 30 38 32 30Z" fill="#7BC043"/>
                        <path d="M32 20C40 18 46 22 48 30C40 32 34 28 32 20Z" fill="#F6C343"/>
                        <path d="M32 30C40 28 46 32 48 40C40 42 34 38 32 30Z" fill="#F6C343"/>
                    </svg>
                </div>

                <h1 class="brand-name">PalayTrack</h1>
                <p class="brand-text">
                    Streamline rice mill operations with a simple and organized system for
                    deliveries, inventory tracking, and daily production monitoring.
                </p>
            </div>

            <div class="feature-box">
                <div class="feature-icon">
                    <svg viewBox="0 0 64 64" fill="none">
                        <path d="M32 54V14" stroke="#F6C343" stroke-width="5" stroke-linecap="round"/>
                        <path d="M32 20C24 18 18 22 16 30C24 32 30 28 32 20Z" fill="#7BC043"/>
                        <path d="M32 30C24 28 18 32 16 40C24 42 30 38 32 30Z" fill="#7BC043"/>
                        <path d="M32 20C40 18 46 22 48 30C40 32 34 28 32 20Z" fill="#F6C343"/>
                        <path d="M32 30C40 28 46 32 48 40C40 42 34 38 32 30Z" fill="#F6C343"/>
                    </svg>
                </div>

                <div>
                    <div class="feature-title">Efficient Mill Operations</div>
                    <p class="feature-text">
                        Manage owner and staff access, monitor inventory flow,
                        and keep delivery records updated in one place.
                    </p>
                </div>
            </div>
        </section>

        <section class="login-right">
            <h2 class="form-title">Welcome back</h2>
            <p class="form-subtitle">Sign in to continue to your PalayTrack dashboard.</p>

            @if ($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <label for="email" class="form-label">Email Address</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" class="custom-input" placeholder="you@example.com" value="{{ old('email') }}" required>
                </div>

                <label for="password" class="form-label">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M7 10V8a5 5 0 0110 0v2" stroke="currentColor" stroke-width="2"/>
                            <path d="M6 10h12v10H6V10Z" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" class="custom-input" placeholder="Enter your password" required>

                    <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>

                <div class="login-options">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>

                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login">Log in</button>
            </form>

            <div class="notice">
                <strong>Notice:</strong> Log in using your assigned account.
                Your dashboard will open based on your role.
            </div>

            <div class="footer">
                © 2026 PalayTrack. All rights reserved.
            </div>
        </section>

    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection