<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="Informasi festival, event, dan budaya Kota Palopo.">
    <link rel="stylesheet" href="{{ asset('css/proposal-ui.css') }}?v={{ filemtime(public_path('css/proposal-ui.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}?v={{ filemtime(public_path('css/forms.css')) }}">
    <title>@yield('title', 'Sistem Informasi Festival & Event Budaya Kota Palopo')</title>
</head>
<body>
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <header class="public-header">
        <a class="public-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/palopo-seal.jpeg') }}" alt="Lambang Kota Palopo">
            <span>SISTEM INFORMASI<br>FESTIVAL &amp; EVENT BUDAYA KOTA PALOPO</span>
        </a>
        <button class="nav-toggle" aria-label="Buka menu" aria-expanded="false">☰</button>
        <nav class="public-nav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" @class(['is-current' => request()->routeIs('home')]) @if (request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
            <a href="{{ route('events.index') }}" @class(['is-current' => request()->routeIs('events.*')]) @if (request()->routeIs('events.*')) aria-current="page" @endif>Festival &amp; Event</a>
            <a href="{{ route('news.index') }}" @class(['is-current' => request()->routeIs('news.*')]) @if (request()->routeIs('news.*')) aria-current="page" @endif>Berita</a>
            <a href="{{ route('gallery') }}" @class(['is-current' => request()->routeIs('gallery')]) @if (request()->routeIs('gallery')) aria-current="page" @endif>Galeri</a>
            <a href="{{ route('calendar') }}" @class(['is-current' => request()->routeIs('calendar')]) @if (request()->routeIs('calendar')) aria-current="page" @endif>Kalender Event</a>
            <a href="{{ route('announcements') }}" @class(['is-current' => request()->routeIs('announcements')]) @if (request()->routeIs('announcements')) aria-current="page" @endif>Pengumuman</a>
            <a href="{{ route('about') }}" @class(['is-current' => request()->routeIs('about')]) @if (request()->routeIs('about')) aria-current="page" @endif>Tentang Kami</a>
            <a href="{{ route('contact') }}" @class(['is-current' => request()->routeIs('contact')]) @if (request()->routeIs('contact')) aria-current="page" @endif>Kontak</a>
        </nav>
        <a class="header-login" href="/admin/login"><span class="user-icon" aria-hidden="true">♙</span> Masuk</a>
    </header>

    <main id="main-content">@yield('content')</main>

    <footer class="public-footer">
        <p class="footer-copyright">© {{ date('Y') }} Dinas Pariwisata Kota Palopo. All rights reserved.</p>
        <div class="footer-lower-row">
            <nav class="footer-links" aria-label="Informasi dan bantuan">
                <span>Kebijakan Privasi</span>
                <span>Syarat &amp; Ketentuan</span>
                <a href="{{ route('contact') }}">Bantuan</a>
            </nav>
            <div class="footer-social" aria-label="Media sosial Dinas Pariwisata Kota Palopo">
                <span class="footer-social-link" title="Instagram" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.4" cy="6.7" r=".8" fill="currentColor" stroke="none"/></svg>
                </span>
                <span class="footer-social-link" title="Facebook" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.6 20v-7h2.5l.4-2.8h-2.9V8.4c0-.8.2-1.4 1.5-1.4h1.6V4.5c-.3 0-1.2-.1-2.2-.1-2.2 0-3.8 1.4-3.8 3.9v1.9H8v2.8h2.7v7h2.9Z" fill="currentColor" stroke="none"/></svg>
                </span>
                <span class="footer-social-link" title="YouTube" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 7.2a2.4 2.4 0 0 0-1.7-1.7C17.3 5 12 5 12 5s-5.3 0-6.8.5a2.4 2.4 0 0 0-1.7 1.7C3 8.7 3 12 3 12s0 3.3.5 4.8a2.4 2.4 0 0 0 1.7 1.7C6.7 19 12 19 12 19s5.3 0 6.8-.5a2.4 2.4 0 0 0 1.7-1.7C21 15.3 21 12 21 12s0-3.3-.5-4.8Z"/><path d="m10 15 5-3-5-3v6Z" fill="currentColor" stroke="none"/></svg>
                </span>
                <span class="footer-social-link" title="X" aria-label="X">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5 18.5 19.5M18.5 4.5 5 19.5"/></svg>
                </span>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/portal.js') }}" defer></script>
    <script src="{{ asset('js/agency-registration.js') }}" defer></script>
</body>
</html>
