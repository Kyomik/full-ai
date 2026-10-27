<x-filament-panels::page.simple>
    <style>
        .fi-simple-layout { min-height: 100vh; box-sizing: border-box; justify-content: center; background: #f5f6f7; padding: 12px; }
        .fi-simple-main-ctn { min-height: 0; flex: 0 1 auto; }
        .fi-simple-main { width: 100% !important; max-width: 64rem !important; margin: 0 !important; padding: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important; }
        .fi-simple-page { width: 100%; max-width: 64rem; }
        .fi-simple-page > section { display: block; }
        .fi-simple-page .fi-simple-header { display: none; }
        .login-proposal { min-height: min(39rem, calc(100vh - 24px)); border: 1px solid #d0d3d6; border-radius: 10px; background: #fff; color: #17191b; box-shadow: 0 16px 42px rgba(23, 25, 27, .10); }
        .login-brand { display: flex; flex-direction: column; align-items: center; justify-content: center; border-right: 1px solid #777; padding: 28px; text-align: center; }
        .login-brand h1 { max-width: 25rem; font-size: 1.45rem; line-height: 1.35; font-weight: 700; }
        .login-brand-city { display: flex; width: 100%; max-width: 21rem; align-items: center; gap: 12px; margin: 14px 0 20px; font-weight: 600; }
        .login-brand-city::before, .login-brand-city::after { height: 1px; flex: 1; background: #777; content: ''; }
        .login-brand p { max-width: 20rem; font-size: .95rem; line-height: 1.6; }
        .login-form-panel { display: flex; align-items: center; justify-content: center; padding: 20px 34px; background: #fcfcfc; }
        .login-form-inner { width: 100%; max-width: 24rem; }
        .login-user-icon { width: 3.75rem; height: 3.75rem; margin: 0 auto 6px; color: #25282a; }
        .login-form-inner h2 { margin: 0; text-align: center; font-size: 1.35rem; line-height: 1.4; font-weight: 700; }
        .login-subtitle { max-width: 19rem; margin: 6px auto 14px; text-align: center; font-size: .9rem; line-height: 1.5; }
        .login-rule { height: 1px; margin: 12px 0 16px; background: #c4c4c4; }
        .login-form-inner .fi-fo-field-wrp-label { display: inline-flex !important; visibility: visible !important; opacity: 1 !important; color: #17191b !important; font-weight: 600; }
        .login-form-inner .fi-fo-field-wrp-label span { color: #17191b !important; }
        .login-remember .fi-fo-field-wrp-label,
        .login-remember .fi-fo-field-wrp-label span { color: #25282a !important; }
        .login-remember .fi-fo-field-wrp-label { display: inline-flex; align-items: center; gap: .5rem; cursor: pointer; }
        .login-remember input[type="checkbox"] { accent-color: #25282a; }
        .login-form-inner input.fi-input { color: #17191b !important; -webkit-text-fill-color: #17191b !important; caret-color: #17191b !important; background: #fff !important; }
        .login-form-inner input.fi-input::placeholder { color: #73777b !important; opacity: 1 !important; }
        .login-form-inner input.fi-input:-webkit-autofill { -webkit-text-fill-color: #17191b !important; box-shadow: 0 0 0 1000px #fff inset !important; }
        .login-form-inner .fi-input-wrp { border-color: #888 !important; background: #fff !important; }
        .login-form-inner .fi-input-wrp:focus-within { border-color: #25282a !important; box-shadow: 0 0 0 1px #25282a !important; }
        .login-forgot { color: #17191b; font-size: .8rem; text-decoration: underline; text-underline-offset: 2px; }
        .login-forgot:hover { text-decoration-thickness: 2px; }
        .login-remember input[type="checkbox"] { display: inline-grid !important; width: 1rem; height: 1rem; appearance: none; -webkit-appearance: none; place-content: center; border: 1px solid #6b7280; border-radius: .25rem; background: #fff; }
        .login-remember input[type="checkbox"]::before { width: .6rem; height: .6rem; transform: scale(0); content: '✓'; color: #fff; font-size: .7rem; font-weight: 700; line-height: .6rem; text-align: center; }
        .login-remember input[type="checkbox"]:checked { border-color: #25282a; background: #25282a; }
        .login-remember input[type="checkbox"]:checked::before { transform: scale(1); }
        .login-remember input[type="checkbox"]:focus-visible { outline: 2px solid #6b7280; outline-offset: 2px; }
        .login-submit, .login-home { width: 100%; min-height: 2.65rem; justify-content: center; border: 1px solid #555 !important; border-radius: 6px; background: #fff !important; color: #17191b !important; font-weight: 600; }
        .login-submit .fi-btn-label, .login-home { color: #17191b !important; }
        .login-submit:hover, .login-home:hover { background: #f4f4f4; }
        .login-submit[disabled] { cursor: wait; opacity: .7; }
        .login-or { display: flex; align-items: center; gap: 14px; margin: 12px 0; font-size: .85rem; }
        .login-or::before, .login-or::after { height: 1px; flex: 1; background: #aaa; content: ''; }
        @media (max-width: 767px) {
            .fi-simple-layout { padding: 8px; }
            .login-proposal { min-height: 0; grid-template-columns: 1fr; }
            .login-brand { min-height: 13rem; border-right: 0; border-bottom: 1px solid #777; padding: 24px; }
            .login-brand h1 { font-size: 1.1rem; }
            .login-brand p { font-size: .85rem; }
            .login-form-panel { padding: 22px 18px; }
        }
    </style>

    <div class="login-proposal grid md:grid-cols-2">
        <section class="login-brand">
            <h1>SISTEM INFORMASI<br>FESTIVAL &amp; EVENT BUDAYA</h1>
            <div class="login-brand-city">KOTA PALOPO</div>
            <p>Kelola informasi festival dan event budaya secara terpusat, akurat, dan terpercaya untuk mendukung pariwisata Kota Palopo.</p>
        </section>
        <section class="login-form-panel">
            <div class="login-form-inner">
                <x-filament::icon icon="heroicon-o-user-circle" class="login-user-icon" />
                <h2>LOGIN SUPER ADMIN</h2>
                <p class="login-subtitle">Masuk untuk mengakses dashboard Sistem Informasi Festival &amp; Event Budaya Kota Palopo.</p>
                <div class="login-rule"></div>
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}
                <x-filament-panels::form id="form" wire:submit="authenticate">
                    {{ $this->form }}
                    <x-filament::button type="submit" color="gray" outlined class="login-submit mt-5" wire:target="authenticate" aria-live="polite">
                        LOGIN
                    </x-filament::button>
                </x-filament-panels::form>
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
                <div class="login-or">atau</div>
                <a href="{{ route('home') }}" class="login-home inline-flex items-center gap-2 px-4 py-2 text-sm">
                    <x-filament::icon icon="heroicon-o-home" class="h-4 w-4" />
                    Kembali ke Beranda
                </a>
            </div>
        </section>
    </div>
</x-filament-panels::page.simple>
