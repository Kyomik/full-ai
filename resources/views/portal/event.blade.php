@extends('layouts.portal')

@section('title', $event->title.' — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page event-detail-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a><span>›</span>
        <a href="{{ route('events.index') }}">Festival &amp; Event</a><span>›</span>
        {{ $event->title }}
    </div>

    <header class="event-detail-heading">
        <div>
            <p class="event-detail-meta">{{ $event->category->name }} <span>·</span> {{ $event->starts_on->translatedFormat('d F Y') }}</p>
            <h1>{{ $event->title }}</h1>
            @if($event->theme)<p class="event-detail-theme">{{ $event->theme }}</p>@endif
        </div>
        <a class="outline-button" href="{{ route('calendar', ['month' => $event->starts_on->format('Y-m')]) }}">Lihat kalender</a>
    </header>

    <section class="event-detail-layout">
        <main class="event-detail-main">
            @if($event->poster)
                <img class="event-detail-poster" src="{{ asset('storage/'.$event->poster) }}" alt="Poster {{ $event->title }}">
            @endif

            <section class="event-detail-section event-about-card">
                <h2>Tentang acara</h2>
                <div class="rich-copy">{!! nl2br(e($event->description)) !!}</div>
            </section>

            @if($event->galleryItems->isNotEmpty())
                <section class="event-detail-section event-gallery-section">
                    <div class="event-section-heading">
                        <h2>Galeri acara</h2>
                    </div>
                    <div class="event-gallery-grid">
                        @foreach($event->galleryItems->take(6) as $media)
                            @php($isVideo = $media->type === 'video' || in_array(strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION)), ['mp4', 'webm', 'mov', 'm4v'], true))
                            <figure class="event-gallery-item {{ $isVideo ? 'is-video' : '' }}">
                                @if($isVideo)
                                    <video controls preload="metadata" aria-label="Video {{ $media->title }}"><source src="{{ asset('storage/'.$media->file_path) }}">Browser Anda tidak mendukung pemutaran video.</video>
                                @else
                                    <a href="{{ asset('storage/'.$media->file_path) }}" target="_blank" rel="noopener" aria-label="Lihat foto {{ $media->title }}"><img src="{{ asset('storage/'.$media->file_path) }}" alt="{{ $media->title }}" loading="lazy"></a>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                    <a class="event-gallery-all" href="{{ route('gallery.album', $event) }}">Lihat seluruh galeri acara <span aria-hidden="true">→</span></a>
                </section>
            @endif

            @if($event->documents->isNotEmpty())
                <section class="event-detail-section event-documents-section">
                    <h2>Dokumen pendukung</h2>
                    <div class="event-document-list">
                        @foreach($event->documents as $document)
                            @php($extension = strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION) ?: 'FILE'))
                            <a class="event-document-item" href="{{ asset('storage/'.$document->file_path) }}" target="_blank" rel="noopener">
                                <span class="event-document-type">{{ $extension }}</span>
                                <span class="event-document-name"><strong>{{ $document->name }}</strong><small>{{ $extension }} · Buka dokumen</small></span>
                                <span class="event-document-open" aria-hidden="true">↗</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <aside class="event-detail-sidebar">
            <section class="event-info-card">
                <h2>Informasi acara</h2>
                <h3>{{ $event->venue }}</h3>
                <p class="event-info-address">{{ $event->address }}</p>
                <dl class="event-info-facts">
                    <div><dt>Tanggal</dt><dd>{{ $event->starts_on->translatedFormat('d F Y') }}{{ $event->ends_on->ne($event->starts_on) ? ' — '.$event->ends_on->translatedFormat('d F Y') : '' }}</dd></div>
                    @if($event->starts_at)<div><dt>Waktu</dt><dd>{{ substr((string) $event->starts_at, 0, 5) }} WITA</dd></div>@endif
                    <div><dt>Penyelenggara</dt><dd>{{ $event->agency->name }}</dd></div>
                </dl>
                @if($event->latitude && $event->longitude)
                    <a class="event-map-link" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $event->latitude }}&amp;mlon={{ $event->longitude }}#map=16/{{ $event->latitude }}/{{ $event->longitude }}">Buka lokasi di peta ↗</a>
                @endif
            </section>
        </aside>
    </section>
</div>
@endsection
