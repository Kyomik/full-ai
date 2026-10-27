@extends('layouts.portal')

@section('title', 'Pengumuman — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page announcement-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a><span>›</span>Pengumuman
    </div>

    <header class="announcement-hero">
        <span class="announcement-hero-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9ZM10 21h4" />
            </svg>
        </span>
        <div class="announcement-hero-copy">
            <span class="announcement-eyebrow">INFORMASI RESMI</span>
            <h1>Pengumuman</h1>
            <p>Informasi resmi terkait festival, event, dan layanan budaya Kota Palopo.</p>
        </div>
        <div class="announcement-count" aria-label="{{ $items->total() }} pengumuman">
            <strong>{{ $items->total() }}</strong>
            <span>Pengumuman</span>
        </div>
    </header>

    <div class="announcement-layout">
        <section class="announcement-content" aria-labelledby="announcement-list-heading">
            <div class="announcement-section-heading">
                <div>
                    <h2 id="announcement-list-heading">Informasi terbaru</h2>
                    <p>Pengumuman yang telah diterbitkan untuk masyarakat.</p>
                </div>
            </div>

            <div class="announcement-list">
                @forelse($items as $item)
                    <article class="announcement-card">
                        <div class="announcement-card-meta">
                            <span class="announcement-type">Pengumuman</span>
                            <time datetime="{{ $item->published_on?->toDateString() }}">
                                {{ $item->published_on?->translatedFormat('d F Y') ?? 'Informasi resmi' }}
                            </time>
                        </div>
                        <h3>{{ $item->title }}</h3>
                        <div class="announcement-card-body">{!! nl2br(e(strip_tags($item->body))) !!}</div>
                    </article>
                @empty
                    <div class="announcement-empty">
                        <span aria-hidden="true">▤</span>
                        <h3>Belum ada pengumuman</h3>
                        <p>Pengumuman resmi akan muncul di halaman ini setelah diterbitkan.</p>
                    </div>
                @endforelse
            </div>

            {{ $items->links('portal.pagination') }}
        </section>

        <aside class="announcement-sidebar" aria-label="Kategori event">
            <h2>Kategori Event</h2>
            <a class="announcement-event-category" href="{{ route('events.index') }}">
                <span>Semua event</span>
                <span aria-hidden="true">→</span>
            </a>
            @foreach($categories as $category)
                <a class="announcement-event-category" href="{{ route('events.index', ['category' => $category->id]) }}">
                    <span>{{ $category->name }}</span>
                    <span class="announcement-category-count">{{ $category->published_events_count }}</span>
                </a>
            @endforeach
        </aside>
    </div>
</div>
@endsection
