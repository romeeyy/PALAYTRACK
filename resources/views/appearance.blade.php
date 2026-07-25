@extends(Auth::user()->role === 'owner' ? 'layouts.owner' : 'layouts.staff')

@section('content')
<style>
    .appearance-page { max-width: 980px; margin: 0 auto; }
    .appearance-header { margin-bottom: 24px; }
    .appearance-title { margin: 0 0 6px; font-size: 2rem; font-weight: 900; color: var(--text-dark); }
    .appearance-subtitle { margin: 0; color: var(--text-muted); }
    .appearance-card { background: #fff; border: 1px solid var(--border-soft); border-radius: 20px; padding: 26px; box-shadow: var(--shadow-soft); }
    .appearance-card h2 { margin: 0 0 5px; font-size: 1.25rem; font-weight: 850; }
    .appearance-card > p { margin: 0 0 22px; color: var(--text-muted); }
    .appearance-section + .appearance-section { margin-top: 26px; padding-top: 24px; border-top: 1px solid #e5e7eb; }
    .appearance-section h2 { margin: 0 0 5px; font-size: 1.25rem; font-weight: 850; }
    .appearance-section > p { margin: 0 0 18px; color: var(--text-muted); }
    .mode-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .mode-option { position: relative; cursor: pointer; }
    .mode-option input { position: absolute; opacity: 0; pointer-events: none; }
    .mode-choice { min-height: 82px; padding: 15px; border: 2px solid #e2e8f0; border-radius: 14px; display: flex; gap: 11px; align-items: flex-start; background: #fff; }
    .mode-choice svg { width: 20px; height: 20px; color: #168344; flex: 0 0 auto; margin-top: 1px; }
    .mode-option input:checked + .mode-choice { border-color: #168344; box-shadow: 0 0 0 3px rgba(22,131,68,.10); }
    .mode-copy strong { display: block; color: #0f172a; }
    .mode-copy span { display: block; color: #64748b; font-size: .8rem; margin-top: 3px; }
    .theme-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .theme-option { position: relative; cursor: pointer; }
    .theme-option input { position: absolute; opacity: 0; pointer-events: none; }
    .theme-choice { display: block; height: 100%; padding: 16px; border: 2px solid #e2e8f0; border-radius: 16px; background: #fff; transition: .18s ease; }
    .theme-option input:checked + .theme-choice { border-color: #168344; box-shadow: 0 0 0 3px rgba(22,131,68,.10); }
    .theme-preview { height: 112px; border-radius: 11px; overflow: hidden; border: 1px solid #dbe3ea; margin-bottom: 14px; display: grid; grid-template-columns: 28% 1fr; }
    .preview-sidebar { background: #166534; }
    .preview-body { padding: 12px; background: #f5f7fa; }
    .preview-bar, .preview-block { display: block; border-radius: 5px; background: #fff; }
    .preview-bar { height: 16px; margin-bottom: 10px; }
    .preview-block { height: 48px; }
    .preview-classic .preview-sidebar { background: #166534; }
    .preview-forest .preview-sidebar { background: #294f2f; }
    .preview-emerald .preview-sidebar { background: #047857; }
    .theme-name { display: flex; align-items: center; gap: 8px; font-weight: 850; color: #0f172a; }
    .theme-name svg { width: 17px; height: 17px; color: #168344; }
    .theme-description { display: block; margin-top: 5px; color: #64748b; font-size: .85rem; line-height: 1.45; }
    .appearance-actions { display: flex; justify-content: flex-end; margin-top: 22px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
    .save-theme { min-height: 46px; padding: 0 20px; border: 0; border-radius: 11px; background: #168344; color: #fff; font-weight: 850; }
    .appearance-success { margin-bottom: 18px; padding: 13px 16px; border-radius: 12px; background: #ecfdf3; color: #166534; border: 1px solid #bbf7d0; font-weight: 700; }
    @media (max-width: 760px) { .theme-options,.mode-options { grid-template-columns: 1fr; } .appearance-card { padding: 20px; } }
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
                <p>Choose the green accent used for navigation and primary actions.</p>
                <div class="theme-options">
                @foreach([
                    'classic' => ['sprout', 'Classic Green', 'The original PalayTrack green theme.'],
                    'forest' => ['trees', 'Deep Forest', 'A deeper, calm green for a professional look.'],
                    'emerald' => ['leaf', 'Fresh Emerald', 'A brighter green accent with a clean feel.'],
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
@endsection
