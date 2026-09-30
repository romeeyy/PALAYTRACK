<!DOCTYPE html>
<html lang="en" data-accent="{{ Auth::check() ? (Auth::user()->theme_preference ?? 'classic') : 'classic' }}" data-display-preference="{{ Auth::check() ? (Auth::user()->display_mode ?? 'light') : 'light' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PalayTrack - Owner</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('components.theme-loader')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('components.fonts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --sidebar-width: 250px;
            --green-dark: #1f4d17;
            --green-main: #2f5d1e;
            --green-soft: rgba(255, 255, 255, 0.12);
            --page-bg: #f5f7fa;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-soft: #e5e7eb;
            --shadow-soft: 0 14px 35px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            font-family: var(--bs-body-font-family);
            color: var(--text-dark);
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            min-height: calc(100vh - 12px);

            /* METALLIC GREEN BLEND */
            background: linear-gradient(145deg,
                    #0f3d1c 0%,
                    #166534 25%,

                    #166534 75%,
                    #0f3d1c 100%);

            color: #ffffff;
            padding: 20px 14px;
            position: fixed;
            top: 6px;
            left: 6px;
            border-radius: 22px;

            /* metallic depth */
            box-shadow:
                inset 0 2px 4px rgba(255, 255, 255, 0.15),
                inset 0 -2px 6px rgba(0, 0, 0, 0.3),
                0 12px 30px rgba(0, 0, 0, 0.25);

            border: 1px solid rgba(255, 255, 255, 0.2);

            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 35%;

            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0.18),
                    transparent);

            border-radius: 22px 22px 0 0;
            pointer-events: none;
        }

        .brand-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 8px;
            padding: 8px 8px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 16px;
        }

        .brand-logo {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.14);
            border: 2px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 800;
            margin: 4px 0 0;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #dcebd6;
            margin: 2px 0 0;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.55);
            margin: 14px 10px 8px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 13px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.18s ease;
        }

        .nav-link-custom:hover {
            background: var(--green-soft);
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-link-custom.active {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            box-shadow: inset 3px 0 0 rgba(255, 255, 255, 0.85);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .menu-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2.3;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 10px 4px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.68);
            font-size: 11px;
            line-height: 1.4;
        }

        .main-content {
            flex: 1;
            margin-left: calc(var(--sidebar-width) + 10px);
            padding: 18px 28px 32px;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 58px;
            padding: 0 14px;
            margin-bottom: 18px;
            background: linear-gradient(135deg, rgba(255,255,255,.94) 0%, rgba(239,248,241,.94) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(185, 210, 189, 0.9);
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(24, 65, 35, 0.10);
            position: relative;
            z-index: 9999;
            overflow: visible;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 10000;
        }

        .mobile-menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            border: 1px solid #d5e1d8;
            border-radius: 12px;
            background: #fff;
            color: #276b16;
            cursor: pointer;
        }

        .top-icon-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border: 1px solid var(--border-soft);
            border-radius: 14px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .top-icon-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.1);
        }

        .top-icon-btn svg {
            width: 18px;
            height: 18px;
        }

        .top-icon-btn::after {
            content: "";
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            background: #ef4444;
            border: 2px solid #ffffff;
            border-radius: 50%;
        }

        .profile-dropdown {
            position: relative;
            z-index: 10000;
        }

        .profile-trigger {
            height: 38px;
            display: flex;
            align-items: center;
            gap: 7px;
            border: 1px solid var(--border-soft);
            border-radius: 999px;
            background: #ffffff;
            padding: 4px 10px 4px 4px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
            transition: all 0.18s ease;
        }

        .profile-trigger:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.1);
        }

        .avatar-small {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #2f5d1e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            overflow: hidden;
        }

        .avatar-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-trigger svg {
            width: 15px;
            height: 15px;
            color: #334155;
        }

        .profile-menu {
            position: absolute;
            top: 48px;
            right: 0;
            width: 210px;
            background: #ffffff;
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 8px;
            display: none;
            box-shadow: var(--shadow-soft);
            z-index: 10001;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu-header {
            padding: 10px 10px 8px;
            border-bottom: 1px solid #eef2f7;
            margin-bottom: 6px;
        }

        .profile-menu-name {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .profile-menu-role {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .profile-menu a,
        .profile-menu button {
            width: 100%;
            border: 0;
            background: transparent;
            color: #1f2937;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .profile-menu a:hover,
        .profile-menu button:hover {
            background: #f3f6f4;
        }

        .profile-menu svg {
            width: 16px;
            height: 16px;
        }

        .logout-item {
            color: #dc2626 !important;
        }

        .main-content h1,
        .main-content .h1 {
            font-size: 31px;
            font-weight: 850;
            letter-spacing: -0.04em;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .main-content h2,
        .main-content .h2 {
            font-size: 24px;
            font-weight: 850;
            letter-spacing: -0.03em;
        }

        .main-content p {
            color: #64748b;
        }

        .card,
        .dashboard-card,
        .insight-card {
            border: 1px solid #eef2f7 !important;
            border-radius: 20px !important;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.07) !important;
            position: relative;
            z-index: 1;
        }

        .card:hover,
        .dashboard-card:hover,
        .insight-card:hover {
            transform: translateY(-2px);
            transition: 0.18s ease;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.1) !important;
        }

        @media (max-width: 991px) {
            :root {
                --sidebar-width: 230px;
            }

            .sidebar {
                padding: 18px 12px;
            }

            .nav-link-custom {
                font-size: 13px;
                padding: 10px 11px;
            }

            .main-content {
                margin-left: calc(var(--sidebar-width) + 10px);
                padding: 18px 22px;
            }
        }

        @media (max-width: 768px) {
            body { overflow-x: hidden; }
            .app-wrapper { display: block; }
            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1200;
                width: min(82vw, 300px);
                min-height: 100dvh;
                max-height: 100dvh;
                border-radius: 0 20px 20px 0;
                transform: translateX(-105%);
                transition: transform .22s ease;
                overflow-y: auto;
            }

            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                z-index: 1100;
                display: block;
                background: rgba(15, 23, 42, .42);
                opacity: 0;
                pointer-events: none;
                transition: opacity .22s ease;
            }
            .sidebar-backdrop.show { opacity: 1; pointer-events: auto; }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 12px;
            }

            .topbar {
                height: 58px;
                margin-bottom: 14px;
                padding: 8px 10px;
            }

            .mobile-menu-toggle { display: inline-flex; position: absolute; left: 10px; }
            .topbar-actions { gap: 8px; }
            .main-content h1, .main-content .h1 { font-size: clamp(1.65rem, 7vw, 2.2rem); }
            .main-content h2, .main-content .h2 { font-size: clamp(1.3rem, 5.5vw, 1.75rem); }
            .page-header { gap: 10px; flex-wrap: wrap; }
            .filter-card, .report-card, .section-card, .form-card, .operations-card { padding: 16px !important; }
            .table-responsive, .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .notification-menu, .profile-menu { max-width: calc(100vw - 24px); }
        }

        @media (max-width: 480px) {
            .main-content { padding: 8px; }
            .topbar { border-radius: 16px; }
            .main-content h1, .main-content .h1 { font-size: 1.55rem; }
            .page-header .btn, .page-header a.btn { width: 100%; }
            .filter-card .btn, .filter-card button { min-height: 44px; }
            .table-responsive table, .table-scroll table { min-width: 700px; }
        }
        @media (min-width: 769px) {
            .sidebar-backdrop { display: none; }
        }
    </style>
</head>

<body>

    <div class="app-wrapper">
        <aside class="sidebar">
            <div class="brand-box">
                @php
                    $logoTheme = in_array(Auth::user()->theme_preference ?? 'classic', ['classic', 'forest', 'emerald', 'olive', 'sage', 'palay'], true)
                        ? Auth::user()->theme_preference
                        : 'classic';
                @endphp
                <div class="brand-logo">
                    <img src="{{ asset('images/theme-logos/jk-logo-' . $logoTheme . '.webp') }}"
                         data-logo-base="{{ asset('images/theme-logos') }}"
                         alt="JK Diez Rice Mill Logo">
                </div>

                <div>
                    <h4 class="brand-title">PalayTrack</h4>
                    <p class="brand-subtitle">Rice Mill Management</p>
                </div>
            </div>

            <div class="nav-section-label">Main Menu</div>

            <nav class="nav-links">
                <a href="/owner/dashboard" class="nav-link-custom {{ request()->is('owner/dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="layout-dashboard"></i></span>
                    <span>Dashboard</span>
                </a>

                <a href="/owner/deliveries" class="nav-link-custom {{ request()->is('owner/deliveries') || request()->is('owner/delivery-details*') || request()->is('owner/record-delivery*') || request()->is('owner/claim-stub*') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="truck"></i></span>
                    <span>Deliveries</span>
                </a>

                <a href="/owner/inventory" class="nav-link-custom {{ request()->is('owner/inventory*') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="package"></i></span>
                    <span>Inventory</span>
                </a>

                <a href="/owner/reports" class="nav-link-custom {{ request()->is('owner/reports*') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="file-text"></i></span>
                    <span>Reports</span>
                </a>


                <div class="nav-section-label">Management</div>

                <a href="/owner/rice-types" class="nav-link-custom {{ request()->is('owner/rice-types*') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="sprout"></i></span>
                    <span>Rice Types</span>
                </a>

                <a href="{{ route('owner.payment-records') }}" class="nav-link-custom {{ request()->is('owner/payment-records') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="receipt-text"></i></span>
                    <span>Transactions</span>
                </a>

                <a href="{{ route('owner.staff-accounts') }}" class="nav-link-custom {{ request()->is('owner/staff-accounts*') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="users"></i></span>
                    <span>Staff Accounts</span>
                </a>
                
                <a href="{{ route('owner.clients') }}"
                    class="nav-link-custom {{ request()->is('owner/clients*') ? 'active' : '' }}">

                    <span class="menu-icon">
                        <i data-lucide="users-round"></i>
                    </span>

                    <span>Clients</span>
                </a>

                <a href="{{ route('owner.settings') }}" class="nav-link-custom {{ request()->is('owner/settings') ? 'active' : '' }}">
                    <span class="menu-icon"><i data-lucide="settings"></i></span>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                Internal rice mill management system
            </div>
        </aside>
        <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-actions">
                    <button class="mobile-menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" onclick="toggleSidebar()">
                        <i data-lucide="menu"></i>
                    </button>
                    @include('components.notification-center')

                    <div class="profile-dropdown">
                        <button class="profile-trigger" type="button" onclick="toggleProfileMenu()">
                            <span class="avatar-small">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                @endif
                            </span>
                            <i data-lucide="chevron-down"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-menu-header">
                                <div class="profile-menu-name">{{ Auth::user()->name }}</div>
                                <div class="profile-menu-role">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>

                            <a href="{{ route('owner.profile') }}">
                                <i data-lucide="user"></i>
                                My Profile
                            </a>

                            <a href="{{ route('appearance.edit') }}">
                                <i data-lucide="palette"></i>
                                Appearance
                            </a>

                            <form method="POST" action="{{ route('logout') }}"
                                  data-confirm-title="Log out of PalayTrack?"
                                  data-confirm-message="Are you sure you want to log out of your account?"
                                  data-confirm-button="Yes, Log Out"
                                  data-confirm-variant="danger">
                                @csrf
                                <button type="submit" class="logout-item">
                                    <i data-lucide="log-out"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
            @include('components.theme-styles')
        </main>
    </div>

    @include('components.confirm-modal')

    <script>
        lucide.createIcons();

        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('show');
        }

        function toggleSidebar(force) {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const button = document.querySelector('.mobile-menu-toggle');
            const open = typeof force === 'boolean' ? force : !sidebar.classList.contains('mobile-open');
            sidebar.classList.toggle('mobile-open', open);
            backdrop.classList.toggle('show', open);
            button?.setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        document.getElementById('sidebarBackdrop')?.addEventListener('click', () => toggleSidebar(false));
        document.querySelectorAll('.sidebar .nav-link-custom').forEach(link => link.addEventListener('click', () => toggleSidebar(false)));

        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const menu = document.getElementById('profileMenu');

            if (dropdown && !dropdown.contains(event.target)) {
                menu.classList.remove('show');
            }

            const notificationDropdown = document.getElementById('notificationDropdown');
            const notificationMenu = document.getElementById('notificationMenu');
            if (notificationDropdown && !notificationDropdown.contains(event.target)) {
                notificationMenu.classList.remove('show');
                document.querySelector('.notification-trigger')?.setAttribute('aria-expanded', 'false');
            }
        });
    </script>

</body>

</html>
