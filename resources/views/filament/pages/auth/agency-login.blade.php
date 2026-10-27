<x-filament-panels::page.simple>
    <style>
        .fi-simple-layout { min-height: 100vh; min-height: 100dvh; box-sizing: border-box; justify-content: center; background: #f5f6f7; padding: 12px; }
        .fi-simple-main-ctn { min-height: 0; flex: 0 1 auto; }
        .fi-simple-main { width: 100% !important; max-width: 70rem !important; margin: 0 !important; padding: 0 !important; border: 0 !important; background: transparent !important; box-shadow: none !important; }
        .fi-simple-page { width: 100%; max-width: 70rem; }
        .fi-simple-page > section { display: block; }
        .fi-simple-page .fi-simple-header { display: none; }
        .agency-login { min-height: min(39rem, calc(100dvh - 24px)); overflow: hidden; border: 1px solid #d0d3d6; border-radius: .75rem; background: #fff; color: #17191b; box-shadow: 0 16px 42px rgba(23, 25, 27, .10); }
        .agency-login-brand { display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #858585; padding: 1.5rem 2.5rem; }
        .agency-login-branding { display: flex; align-items: center; gap: .875rem; font-size: .875rem; font-weight: 700; line-height: 1.4; }
        .agency-login-branding img { width: 4.25rem; height: 4.25rem; object-fit: contain; }
        .agency-login-pitch { max-width: 27rem; margin: auto 0; padding: 1.25rem 0; }
        .agency-login-pitch h1 { margin: 0; font-size: clamp(2rem, 3.3vw, 2.75rem); font-weight: 700; line-height: 1.2; letter-spacing: -.03em; }
        .agency-login-pitch p { max-width: 25rem; margin-top: .875rem; color: #45484b; font-size: .95rem; line-height: 1.65; }
        .agency-login-panel { display: flex; align-items: center; justify-content: center; padding: 1.5rem 2.5rem; background: #fcfcfc; }
        .agency-login-inner { width: 100%; max-width: 27rem; }
        .agency-login-logo { display: block; width: 3.75rem; height: 3.75rem; margin: 0 auto .5rem; object-fit: contain; }
        .agency-login-inner h2 { margin: 0; text-align: center; font-size: 1.5rem; font-weight: 700; line-height: 1.3; }
        .agency-login-subtitle { max-width: 25rem; margin: .375rem auto 1rem; color: #45484b; text-align: center; font-size: .9rem; line-height: 1.55; }
        .agency-login-inner .fi-fo-field-wrp-label { display: inline-flex !important; visibility: visible !important; opacity: 1 !important; color: #17191b !important; font-weight: 600; }
        .agency-login-inner .fi-fo-field-wrp-label span { color: #17191b !important; }
        .agency-login-inner .login-remember .fi-fo-field-wrp-label,
        .agency-login-inner .login-remember .fi-fo-field-wrp-label span { color: #25282a !important; }
        .agency-login-inner .login-remember .fi-fo-field-wrp-label { display: inline-flex; align-items: center; gap: .5rem; cursor: pointer; }
        .agency-login-inner .login-remember input[type="checkbox"] { accent-color: #25282a; }
        .agency-login-inner input.fi-input { color: #17191b !important; -webkit-text-fill-color: #17191b !important; caret-color: #17191b !important; background: #fff !important; }
        .agency-login-inner input.fi-input::placeholder { color: #73777b !important; opacity: 1 !important; }
        .agency-login-inner input.fi-input:-webkit-autofill { -webkit-text-fill-color: #17191b !important; box-shadow: 0 0 0 1000px #fff inset !important; }
        .agency-login-inner .fi-input-wrp { border-color: #9a9a9a !important; background: #fff !important; }
        .agency-login-inner .fi-input-wrp:focus-within { border-color: #25282a !important; box-shadow: 0 0 0 1px #25282a !important; }
        .agency-login-forgot { color: #17191b; font-size: .875rem; text-decoration: underline; text-underline-offset: 2px; }
        .agency-login-forgot:hover { text-decoration-thickness: 2px; }
        .login-forgot { color: #17191b; font-size: .875rem; text-decoration: underline; text-underline-offset: 2px; }
        .login-forgot:hover { text-decoration-thickness: 2px; }
        .login-remember input[type="checkbox"] { display: inline-grid !important; width: 1rem; height: 1rem; appearance: none; -webkit-appearance: none; place-content: center; border: 1px solid #6b7280; border-radius: .25rem; background: #fff; }
        .login-remember input[type="checkbox"]::before { width: .6rem; height: .6rem; transform: scale(0); content: '✓'; color: #fff; font-size: .7rem; font-weight: 700; line-height: .6rem; text-align: center; }
        .login-remember input[type="checkbox"]:checked { border-color: #25282a; background: #25282a; }
        .login-remember input[type="checkbox"]:checked::before { transform: scale(1); }
        .login-remember input[type="checkbox"]:focus-visible { outline: 2px solid #6b7280; outline-offset: 2px; }
        .agency-login-submit, .agency-login-register { width: 100%; min-height: 2.65rem; justify-content: center; border: 1px solid #333 !important; border-radius: .5rem; font-weight: 600; }
        .agency-login-submit { margin-top: .875rem; background: #17191b !important; color: #fff !important; }
        .agency-login-submit:hover { background: #343638 !important; }
        .agency-login-submit[disabled] { cursor: wait; opacity: .7; }
        .agency-login-register { background: #fff !important; color: #17191b !important; }
        .agency-login-register:hover { background: #f4f4f4 !important; }
        .agency-login-divider { display: flex; align-items: center; gap: .75rem; margin: .875rem 0; color: #45484b; font-size: .875rem; }
        .agency-login-divider::before, .agency-login-divider::after { height: 1px; flex: 1; background: #b8b8b8; content: ''; }
        .agency-login-notice { margin-bottom: 1rem; border-radius: .375rem; background: #f0fdf4; padding: .75rem 1rem; color: #166534; font-size: .875rem; }
        @media (max-width: 767px) {
            .fi-simple-layout { padding: .5rem; }
            .agency-login { min-height: 0; grid-template-columns: minmax(0, 1fr); }
            .agency-login-brand { min-height: 0; border-right: 0; border-bottom: 1px solid #858585; padding: 1rem 1.25rem; }
            .agency-login-branding { font-size: .75rem; }
            .agency-login-branding img { width: 3rem; height: 3rem; }
            .agency-login-pitch { margin: 1rem 0 .125rem; padding: 0; }
            .agency-login-pitch h1 { font-size: 1.5rem; }
            .agency-login-pitch p { margin-top: .5rem; font-size: .825rem; line-height: 1.5; }
            .agency-login-panel { padding: 1rem 1.25rem; }
            .agency-login-logo { width: 3rem; height: 3rem; margin-bottom: .375rem; }
            .agency-login-inner h2 { font-size: 1.25rem; }
            .agency-login-subtitle { margin-bottom: .75rem; font-size: .825rem; }
            .agency-login-divider { margin: .625rem 0; }
        }
        @media (max-height: 760px) and (min-width: 768px) {
            .agency-login { min-height: calc(100dvh - 24px); }
            .agency-login-brand { padding-block: 1rem; }
            .agency-login-pitch h1 { font-size: clamp(1.75rem, 3vw, 2.3rem); }
            .agency-login-panel { padding-block: .75rem; }
            .agency-login-logo { width: 3rem; height: 3rem; }
            .agency-login-subtitle { margin-bottom: .625rem; }
            .agency-login-divider { margin: .5rem 0; }
        }
    </style>

    <div class="agency-login grid md:grid-cols-2">
        <section class="agency-login-brand">
            <a class="agency-login-branding" href="{{ route('home') }}">
                <img src="{{ asset('images/palopo-seal.jpeg') }}" alt="Lambang Kota Palopo">
                <span>SISTEM INFORMASI<br>FESTIVAL &amp; EVENT BUDAYA<br>KOTA PALOPO</span>
            </a>
            <div class="agency-login-pitch">
                <h1>Kelola Festival dan Event Budaya dengan Mudah</h1>
                <p>Sistem informasi terintegrasi untuk penyelenggara dalam mengelola data festival, event, dan kegiatan budaya di Kota Palopo.</p>
            </div>
        </section>

        <section class="agency-login-panel">
            <div class="agency-login-inner">
                <img class="agency-login-logo" src="{{ asset('images/palopo-seal.jpeg') }}" alt="Lambang Kota Palopo">
                <h2>Login Admin Penyelenggara</h2>
                <p class="agency-login-subtitle">Masuk ke akun Anda untuk mengelola data festival dan event budaya.</p>

                @if (session('status'))
                    <div class="agency-login-notice" role="status">{{ session('status') }}</div>
                @endif

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}
                <x-filament-panels::form id="form" wire:submit="authenticate">
                    {{ $this->form }}
                    <x-filament::button type="submit" class="agency-login-submit" wire:target="authenticate" aria-live="polite">
                        Masuk
                    </x-filament::button>
                </x-filament-panels::form>
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

                <div class="agency-login-divider">Belum punya akun?</div>
                <a href="{{ route('register.agency') }}" class="agency-login-register inline-flex items-center gap-2 px-4 py-2 text-sm">
                    <x-filament::icon icon="heroicon-o-user-plus" class="h-5 w-5" />
                    Daftar sebagai Admin Penyelenggara
                </a>
            </div>
        </section>
    </div>
</x-filament-panels::page.simple>
