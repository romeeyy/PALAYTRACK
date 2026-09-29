@extends(Auth::user()->role === 'owner' ? 'layouts.owner' : 'layouts.staff')

@section('content')
<style>
    .appearance-page { width: 100%; max-width: 1380px; margin: 0 auto; }
    .appearance-header { margin-bottom: 16px; }
    .appearance-title { margin: 0 0 6px; font-size: 2rem; font-weight: 900; color: var(--text-dark); }
    .appearance-subtitle { margin: 0; color: var(--text-muted); }
    .appearance-card { background: #fff; border: 1px solid var(--border-soft); border-radius: 20px; padding: 20px 24px; box-shadow: var(--shadow-soft); }
    .appearance-card h2 { margin: 0 0 5px; font-size: 1.25rem; font-weight: 850; }
    .appearance-card > p { margin: 0 0 22px; color: var(--text-muted); }
    .appearance-section + .appearance-section { margin-top: 18px; padding-top: 18px; border-top: 1px solid #e5e7eb; }
    .appearance-section h2 { margin: 0 0 5px; font-size: 1.25rem; font-weight: 850; }
    .appearance-section > p { margin: 0 0 12px; color: var(--text-muted); }
    .mode-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .mode-option { position: relative; cursor: pointer; }
    .mode-option input { position: absolute; opacity: 0; pointer-events: none; }
    .mode-choice { position: relative; min-height: 70px; padding: 12px 42px 12px 14px; border: 2px solid #e2e8f0; border-radius: 14px; display: flex; gap: 11px; align-items: flex-start; background: #fff; }
    .mode-choice svg { width: 20px; height: 20px; color: var(--user-accent); flex: 0 0 auto; margin-top: 1px; }
    .mode-option input:checked + .mode-choice { border-color: var(--user-accent); box-shadow: 0 0 0 3px var(--user-accent-ring); }
    .mode-copy strong { display: block; color: #0f172a; }
    .mode-copy span { display: block; color: #64748b; font-size: .8rem; margin-top: 3px; }
    .theme-options { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
    .theme-option { position: relative; cursor: pointer; }
    .theme-option input { position: absolute; opacity: 0; pointer-events: none; }
    .theme-choice { position: relative; display: grid; grid-template-columns: 132px minmax(0, 1fr); grid-template-rows: auto 1fr; column-gap: 14px; align-items: center; height: 100%; min-height: 112px; padding: 12px; border: 2px solid #e2e8f0; border-radius: 16px; background: #fff; transition: .18s ease; }
    .theme-option input:checked + .theme-choice { border-color: var(--user-accent); box-shadow: 0 0 0 3px var(--user-accent-ring); }
    .mode-option input:checked + .mode-choice::after,
    .theme-option input:checked + .theme-choice::after { content: '✓'; position: absolute; top: 10px; right: 10px; width: 24px; height: 24px; display: grid; place-items: center; border-radius: 999px; background: var(--user-accent); color: #fff; font-size: .82rem; font-weight: 900; }
    .theme-preview { grid-row: 1 / 3; width: 132px; height: 86px; border-radius: 11px; overflow: hidden; border: 1px solid #dbe3ea; margin: 0; display: grid; grid-template-columns: 30% 1fr; }
    .preview-sidebar { background: #166534; }
    .preview-body { padding: 9px; background: #f5f7fa; }
    .preview-bar, .preview-block { display: block; border-radius: 5px; background: #fff; }
    .preview-bar { height: 12px; margin-bottom: 8px; }
    .preview-block { height: 36px; }
    .preview-classic .preview-sidebar { background: #166534; }
    .preview-forest .preview-sidebar { background: #294f2f; }
    .preview-emerald .preview-sidebar { background: #047857; }
    .preview-olive .preview-sidebar { background: #53651f; }
    .preview-sage .preview-sidebar { background: #416b57; }
    .preview-palay .preview-sidebar { background: #4d7c0f; }
    .theme-name { align-self: end; display: flex; align-items: center; gap: 8px; font-weight: 850; color: #0f172a; }
    .theme-name svg { width: 17px; height: 17px; color: var(--user-accent); }
    .theme-description { align-self: start; display: block; margin-top: 4px; color: #64748b; font-size: .8rem; line-height: 1.35; }
    .appearance-actions { display: flex; justify-content: flex-end; margin-top: 16px; padding-top: 14px; border-top: 1px solid #e5e7eb; }
    .save-theme { min-height: 46px; padding: 0 20px; border: 0; border-radius: 11px; background: #168344; color: #fff; font-weight: 850; }
    .appearance-success { margin-bottom: 18px; padding: 13px 16px; border-radius: 12px; background: #ecfdf3; color: #166534; border: 1px solid #bbf7d0; font-weight: 700; }
    @media (max-width: 1150px) { .theme-choice { grid-template-columns: 105px minmax(0, 1fr); } .theme-preview { width: 105px; } }
    @media (max-width: 900px) { .theme-options { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 760px) { .theme-options,.mode-options { grid-template-columns: 1fr; } .appearance-card { padding: 18px; } .theme-choice { grid-template-columns: 120px minmax(0, 1fr); } .theme-preview { width: 120px; } }
</style>

<div class="appearance-page">
    <div class="appearance-header">
        <h1 class="appearance-title">Appearance</h1>
        <p class="appearance-subtitle">Choose the accent color used on your PalayTrack account.</p>
    </div>

    @if(session('success'))<div class="appearance-success">{{ session('success') }}</div>@endif

    <div class="appearance-card">
        <form method="POST" action="{{ route('appearance.update') }}"
              data-confirm-title="Change Appearance?"
              data-confirm-message="Apply this theme to your PalayTrack account?"
              data-confirm-button="Apply Theme">
            @csrf
            <div class="appearance-section">
                <h2>Display Mode</h2>
                <p>Choose a light or properly optimized dark workspace.</p>
                <div class="mode-options">
                    @foreach([
                        'light' => ['sun', 'Light', 'Bright workspace for daytime use.'],
                        'dark' => ['moon', 'Dark', 'Reduced glare for low-light use.'],
                        'system' => ['monitor-cog', 'System Default', 'Follow your device setting.'],
                    ] as $value => [$icon, $label, $description])
                        <label class="mode-option">
                            <input type="radio" name="display_mode" value="{{ $value }}"
                                   {{ Auth::user()->display_mode === $value ? 'checked' : '' }}>
                            <span class="mode-choice">
                                <i data-lucide="{{ $icon }}"></i>
                                <span class="mode-copy"><strong>{{ $label }}</strong><span>{{ $description }}</span></span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('display_mode')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>

            <div class="appearance-section">
                <h2>Color Theme</h2>
                <p>Choose a professional accent for navigation, tabs, and primary actions.</p>
                <div class="theme-options">
                @foreach([
                    'classic' => ['sprout', 'Classic Green', 'The original PalayTrack green theme.'],
                    'forest' => ['trees', 'Deep Forest', 'A deeper, calm green for a professional look.'],
                    'emerald' => ['leaf', 'Fresh Emerald', 'A brighter green accent with a clean feel.'],
                    'olive' => ['wheat', 'Harvest Olive', 'An earthy rice-field green with a mature feel.'],
                    'sage' => ['flower-2', 'Calm Sage', 'A soft natural green for a relaxed workspace.'],
                    'palay' => ['sprout', 'Young Palay', 'A lively rice-leaf green inspired by new growth.'],
                ] as $value => [$icon, $label, $description])
                    <label class="theme-option">
                        <input type="radio" name="theme_preference" value="{{ $value }}"
                               {{ Auth::user()->theme_preference === $value ? 'checked' : '' }}>
                        <span class="theme-choice">
                            <span class="theme-preview preview-{{ $value }}">
                                <span class="preview-sidebar"></span>
                                <span class="preview-body"><span class="preview-bar"></span><span class="preview-block"></span></span>
                            </span>
                            <span class="theme-name"><i data-lucide="{{ $icon }}"></i>{{ $label }}</span>
                            <span class="theme-description">{{ $description }}</span>
                        </span>
                    </label>
                @endforeach
                </div>
                @error('theme_preference')<div class="text-danger mt-2">{{ $message }}</div>@enderror
            </div>
            <div class="appearance-actions"><button class="save-theme" type="submit">Apply Theme</button></div>
        </form>
    </div>
</div>
<script>
    (() => {
        const root = document.documentElement;
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
        const applyDisplayPreview = (preference) => {
            root.dataset.theme = preference === 'system'
                ? (systemTheme.matches ? 'dark' : 'light')
                : preference;
        };

        document.querySelectorAll('input[name="display_mode"]').forEach((input) => {
            input.addEventListener('change', () => applyDisplayPreview(input.value));
        });
        document.querySelectorAll('input[name="theme_preference"]').forEach((input) => {
            input.addEventListener('change', () => {
                root.dataset.accent = input.value;
                const logo = document.querySelector('.brand-logo img[data-logo-base]');
                if (logo) {
                    logo.src = `${logo.dataset.logoBase}/jk-logo-${input.value}.webp`;
                }
            });
        });
    })();
</script>
@endsection
