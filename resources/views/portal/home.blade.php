@extends('layouts.portal')

@section('title', 'Beranda — Sistem Informasi Festival & Event Budaya Kota Palopo')

@section('content')
@php
    $featuredEvent = $events->first();
    $otherEvents = $events->skip(1);
@endphp

<div class="page-container home-page home-dashboard">
    <section class="home-hero" aria-labelledby="home-title">
        <div class="home-hero-copy">
            <p class="eyebrow">Portal budaya Kota Palopo · Sulawesi Selatan</p>
            <h1 id="home-title">Temukan Palopo, <em>satu perayaan</em> pada satu waktu.</h1>
            <p class="home-hero-lede">Agenda, kabar, dan jejak budaya lokal dalam satu ruang untuk warga dan pengunjung.</p>
            <form class="hero-search" action="{{ route('events.index') }}" role="search">
                <label class="sr-only" for="home-search">Cari festival atau event</label>
                <span aria-hidden="true">⌕</span>
                <input id="home-search" name="search" placeholder="Cari agenda budaya..." value="{{ request('search') }}">
                <button class="search-submit" aria-label="Cari agenda">⌕</button>
            </form>
            <div class="hero-meta" aria-label="Ringkasan portal">
                <span><b>{{ $events->count() }}</b> agenda terdekat</span>
                <span><b>{{ $categories->count() }}</b> ragam kegiatan</span>
            </div>
        </div>

        <div class="hero-illustration">
            @if($featuredEvent)
                <a class="hero-feature" href="{{ route('events.show', $featuredEvent) }}">
                    @if($featuredEvent->poster)
                        <img src="{{ asset('storage/'.$featuredEvent->poster) }}" alt="Poster {{ $featuredEvent->title }}">
                    @else
                        <span class="hero-feature-fallback" aria-hidden="true"><small>{{ $featuredEvent->category?->name ?? 'Festival & Event' }}</small><strong>Palopo</strong></span>
                    @endif
                    <span class="hero-feature-caption">
                        <small>AGENDA PILIHAN</small>
                        <strong>{{ $featuredEvent->title }}</strong>
                        <span>{{ $featuredEvent->starts_on->translatedFormat('d F Y') }} · {{ $featuredEvent->venue }}</span>
                        <i>Selengkapnya <b>→</b></i>
                    </span>
                </a>
            @else
                <div class="hero-feature-empty"><span>RUANG PERTEMUAN</span><strong>Agenda budaya Palopo akan segera hadir di sini.</strong></div>
            @endif
        </div>
    </section>

    <nav class="home-quick-links" aria-label="Jelajahi portal">
        <a href="{{ route('calendar') }}"><span aria-hidden="true">◫</span><b>Lihat kalender</b><small>Susun rencana kunjungan</small></a>
        <a href="{{ route('gallery') }}"><span aria-hidden="true">◌</span><b>Jelajahi galeri</b><small>Potret dari setiap perayaan</small></a>
        <a href="{{ route('news.index') }}"><span aria-hidden="true">↗</span><b>Baca kabar kota</b><small>Cerita budaya terkini</small></a>
    </nav>

    @if($categories->isNotEmpty())
        <section class="home-section category-section" aria-labelledby="category-heading">
            <div class="section-title-row section-title-row--with-copy">
                <div><h2 id="category-heading">Pilih yang kamu suka</h2></div>
                <a href="{{ route('events.index') }}">Semua agenda <span>→</span></a>
            </div>
            <div class="category-grid">
                @foreach($categories as $category)
                    <a class="category-tile" href="{{ route('events.index', ['category' => $category->id]) }}">
                        <span class="category-icon" aria-hidden="true">{{ ['✦', '⌂', '◇', '▤', '◎', '✳'][$loop->index % 6] }}</span>
                        <strong>{{ $category->name }}</strong><i class="category-arrow" aria-hidden="true">↗</i>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="home-section home-agenda-section" aria-labelledby="agenda-heading">
            <div class="section-title-row section-title-row--with-copy">
                <div><h2 id="agenda-heading">Yang akan datang</h2></div>
            @if($events->isNotEmpty())<a href="{{ route('events.index') }}">Lihat semua <span>→</span></a>@endif
        </div>
        @if($featuredEvent)
            <div class="home-agenda-layout">
                <a class="agenda-feature" href="{{ route('events.show', $featuredEvent) }}">
                    <div class="agenda-feature-date"><span>{{ $featuredEvent->starts_on->translatedFormat('M') }}</span><b>{{ $featuredEvent->starts_on->format('d') }}</b></div>
                    <div><p>{{ $featuredEvent->category?->name ?? 'Festival & Event' }}</p><h3>{{ $featuredEvent->title }}</h3><span>{{ $featuredEvent->venue }}, Palopo</span></div><i aria-hidden="true">→</i>
                </a>
                @if($otherEvents->isNotEmpty())
                    <div class="agenda-list">
                        @foreach($otherEvents as $event)
                            <a class="agenda-list-item" href="{{ route('events.show', $event) }}">
                                <time datetime="{{ $event->starts_on->toDateString() }}"><b>{{ $event->starts_on->format('d') }}</b><span>{{ $event->starts_on->translatedFormat('M') }}</span></time>
                                <span><small>{{ $event->category?->name ?? 'Festival & Event' }}</small><strong>{{ $event->title }}</strong><em>{{ $event->venue }}</em></span><i aria-hidden="true">↗</i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="empty-panel">Belum ada agenda terdekat. Silakan kunjungi kembali halaman ini.</div>
        @endif
    </section>

    @if($articles->isNotEmpty())
        <section class="home-section home-stories" aria-labelledby="stories-heading">
            <div class="section-title-row section-title-row--with-copy">
                <div><h2 id="stories-heading">Cerita dari Palopo</h2></div>
                <a href="{{ route('news.index') }}">Semua berita <span>→</span></a>
            </div>
            <div class="home-story-grid">
                @foreach($articles as $article)
                    <a class="home-story-card" href="{{ route('news.show', $article) }}">
                        <div class="home-story-image">@if($article->image)<img src="{{ asset('storage/'.$article->image) }}" alt="" loading="lazy">@else<span aria-hidden="true">PALOPO</span>@endif</div>
                        <div><time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('d M Y') }}</time><h3>{{ $article->title }}</h3><span>Baca cerita <b>→</b></span></div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="announcement-strip" aria-label="Pengumuman terbaru">
        <div class="announcement-icon" aria-hidden="true">♧</div>
        <div class="announcement-main"><strong>Pengumuman terbaru</strong>@if($announcements->isNotEmpty())<span>{{ $announcements->first()->title }} · {{ Str::limit(strip_tags($announcements->first()->body), 120) }}</span>@else<span>Belum ada pengumuman terbaru.</span>@endif</div>
        @if($announcements->isNotEmpty())<a href="{{ route('announcements') }}">Lihat semua <span>→</span></a>@endif
    </section>
</div>
@endsection
