@php
    $systemNotifications = app(\App\Services\SystemNotificationService::class)->forUser(Auth::user());
    $unreadNotificationCount = $systemNotifications->where('unread', true)->count();
@endphp

<div class="notification-dropdown" id="notificationDropdown">
    <button class="top-icon-btn notification-trigger" type="button"
            aria-label="Open notifications" aria-expanded="false"
            onclick="toggleNotificationMenu(event)">
        <i data-lucide="bell"></i>
        @if($unreadNotificationCount > 0)
            <span class="notification-dot" aria-label="{{ $unreadNotificationCount }} unread notifications"></span>
        @endif
    </button>

    <div class="notification-menu" id="notificationMenu">
        <div class="notification-menu-header">
            <div>
                <strong>Notifications</strong>
                <span>{{ $unreadNotificationCount ? $unreadNotificationCount . ' unread' : 'You are up to date' }}</span>
            </div>
            <form method="POST" action="{{ route('notifications.read') }}">
                @csrf
                <button type="submit" {{ $unreadNotificationCount === 0 ? 'disabled' : '' }}>
                    {{ $unreadNotificationCount > 0 ? 'Mark all as read' : 'All read' }}
                </button>
            </form>
        </div>

        <div class="notification-list">
            @forelse($systemNotifications as $notification)
                <a href="{{ $notification['url'] }}" onclick="markNotificationsRead()"
                   class="notification-item {{ $notification['unread'] ? 'is-unread' : '' }} {{ $notification['tone'] === 'danger' ? 'is-danger' : '' }}">
                    <span class="notification-item-icon {{ $notification['tone'] === 'danger' ? 'is-danger' : '' }}">
                        <i data-lucide="{{ $notification['icon'] }}"></i>
                    </span>
                    <span class="notification-item-copy">
                        <strong>{{ $notification['title'] }}</strong>
                        <span>{{ $notification['message'] }}</span>
                        <small>{{ $notification['date']->format('M d - g:i A') }}</small>
                    </span>
                    @if($notification['unread'])
                        <span class="notification-unread-dot" aria-label="Unread"></span>
                    @endif
                </a>
            @empty
                <div class="notification-empty">
                    <i data-lucide="bell-ring"></i>
                    <strong>No notifications yet</strong>
                    <span>Important system updates will appear here.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .notification-dropdown{position:relative;z-index:10001}
    .notification-trigger::after{display:none!important}
    .notification-dot{position:absolute;top:7px;right:7px;width:8px;height:8px;border-radius:50%;background:#ef4444;border:2px solid #fff}
    .notification-menu{position:absolute;display:none;top:48px;right:0;width:min(360px,calc(100vw - 32px));background:linear-gradient(135deg,#ffffff 0%,#f1f8f2 100%);border:1px solid #cbdccf;border-radius:16px;box-shadow:0 20px 50px rgba(24,65,35,.18);overflow:hidden;text-align:left}
    .notification-menu.show{display:block}
    .notification-menu-header{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:15px 16px;border-bottom:1px solid #cbdccf;background:#ffffff}
    .notification-menu-header>div{display:flex;flex-direction:column}.notification-menu-header strong{font-size:15px;color:#0f172a}.notification-menu-header span{font-size:12px;color:#64748b}
    .notification-menu-header form{margin:0 0 0 auto}
    .notification-menu-header button{border:0;background:transparent;color:var(--user-accent-dark,#15803d);font-size:12px;font-weight:800;padding:6px 8px;border-radius:7px;white-space:nowrap;cursor:pointer}
    .notification-menu-header button:hover{background:var(--user-accent-soft,#ecfdf3)}
    .notification-menu-header button:disabled{color:#94a3b8;cursor:default}
    .notification-list{max-height:390px;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--user-accent,#86a98d) #f1f5f9}
    .notification-list::-webkit-scrollbar{width:8px}.notification-list::-webkit-scrollbar-track{background:#f1f5f9}.notification-list::-webkit-scrollbar-thumb{background:var(--user-accent,#86a98d);border-radius:999px}
    .notification-item{display:flex!important;align-items:flex-start;gap:11px;padding:13px 16px!important;text-decoration:none!important;border-bottom:1px solid #dce9df;color:inherit!important;position:relative}
    .notification-item:hover{background:#eaf6ec}.notification-item.is-unread{background:#e5f5e8}.notification-item.is-danger.is-unread{background:#fff1f2}.notification-item.is-danger:hover{background:#ffe4e6}

    @media (max-width: 768px) {
        .notification-menu {
            position: fixed !important;
            top: 72px !important;
            left: 8px !important;
            right: 8px !important;
            width: auto !important;
            max-width: none !important;
            z-index: 12000 !important;
        }
    }
    .notification-item-icon{flex:0 0 34px;width:34px;height:34px;border-radius:10px;background:var(--user-accent-soft,#eaf7ef);color:var(--user-accent-dark,#15803d);display:flex;align-items:center;justify-content:center}
    .notification-item-icon.is-danger{background:#fff1f2;color:#dc2626}.notification-item-icon svg{width:17px;height:17px}
    .notification-item-copy{min-width:0;display:flex;flex-direction:column;line-height:1.35}.notification-item-copy strong{font-size:13px;color:#0f172a}.notification-item-copy span{font-size:12px;color:#475569;white-space:normal;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.notification-item-copy small{font-size:11px;color:#94a3b8;margin-top:3px}
    .notification-unread-dot{width:7px!important;height:7px!important;flex:0 0 7px;border-radius:50%;background:var(--user-accent,#15803d);margin:5px 0 0 auto!important;display:block!important}.notification-item.is-danger .notification-unread-dot{background:#dc2626}
    .notification-empty{padding:30px 18px;display:flex;align-items:center;flex-direction:column;text-align:center;color:#64748b}.notification-empty svg{width:25px;margin-bottom:8px;color:var(--user-accent,#15803d)}.notification-empty strong{font-size:13px;color:#334155}.notification-empty span{font-size:12px;margin-top:3px}
    html[data-theme="dark"] .notification-menu{background:#172235;border-color:#334155}
    html[data-theme="dark"] .notification-menu-header,html[data-theme="dark"] .notification-item{border-color:#334155}
    html[data-theme="dark"] .notification-menu-header strong,html[data-theme="dark"] .notification-item-copy strong{color:#f8fafc}
    html[data-theme="dark"] .notification-menu-header span,html[data-theme="dark"] .notification-item-copy span{color:#cbd5e1}
    html[data-theme="dark"] .notification-item:hover{background:#1e293b}html[data-theme="dark"] .notification-item.is-unread{background:#173528}
    html[data-theme="dark"] .notification-dot{border-color:#172235}
</style>

<script>
    function toggleNotificationMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('notificationMenu');
        const trigger = document.querySelector('.notification-trigger');
        const willOpen = !menu.classList.contains('show');
        document.getElementById('profileMenu')?.classList.remove('show');
        menu.classList.toggle('show', willOpen);
        trigger?.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    }

    function markNotificationsRead() {
        fetch(@json(route('notifications.read')), {
            method: 'POST',
            keepalive: true,
            headers: {
                'X-CSRF-TOKEN': @json(csrf_token()),
                'Accept': 'application/json'
            }
        });
    }
</script>
