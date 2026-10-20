@extends('layouts.portal')
@section('title','Beranda — Sistem Informasi Festival & Event Budaya Kota Palopo')
@section('content')
<div class="page-container home-page">
    <section class="home-hero">
        <div class="home-hero-copy"><span class="eyebrow">KOTA PALOPO · SULAWESI SELATAN</span><h1>Selamat datang!</h1><h2>Temukan festival & event budaya terbaik di Kota Palopo.</h2><p>Dapatkan informasi lengkap mengenai kegiatan budaya, jadwal pelaksanaan, dan lokasi acara.</p>
            <form class="hero-search" action="{{ route('events.index') }}"><label class="sr-only" for="home-search">Cari festival atau event</label><span>⌕</span><input id="home-search" name="search" placeholder="Cari festival atau event..."><button class="outline-button" type="button" data-filter-toggle>⚲ Filter</button><div class="hero-filter"><label for="home-category">Kategori</label><select id="home-category" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div><button class="search-submit" aria-label="Cari">⌕</button></form>
        </div><div class="hero-illustration"><div class="hero-placeholder"><span>Festival & Event Budaya<br>Kota Palopo</span></div></div>
    </section>

    <section class="home-section category-section"><div class="section-title-row"><h2>Kategori Kegiatan</h2><a href="{{ route('events.index') }}">Lihat semua <span>→</span></a></div><div class="category-grid">@foreach($categories as $category)<a class="category-tile" href="{{ route('events.index',['category'=>$category->id]) }}"><span class="category-icon">{{ ['✧','⌂','◈','▤','◎','✳'][$loop->index%6] }}</span><strong>{{ $category->name }}</strong></a>@endforeach</div></section>

    <section class="home-section"><div class="section-title-row"><div><h2>Event Terdekat</h2><p>Agenda budaya yang akan datang di Kota Palopo.</p></div><a href="{{ route('events.index') }}">Lihat semua <span>→</span></a></div>
        @if($events->isNotEmpty())<div class="home-event-grid">@foreach($events as $event)<a class="home-event-card" href="{{ route('events.show',$event) }}"><div class="home-event-image">@if($event->poster)<img src="{{ asset('storage/'.$event->poster) }}" alt="Poster {{ $event->title }}" loading="lazy">@else<span class="image-placeholder">{{ $event->category->name }}</span>@endif</div><div class="home-event-card-body"><strong>{{ $event->title }}</strong><span class="event-fact">▦ {{ $event->starts_on->translatedFormat('d M Y') }}{{ $event->ends_on->ne($event->starts_on) ? ' – '.$event->ends_on->translatedFormat('d M Y') : '' }}</span><span class="event-fact">⌖ {{ $event->venue }}, Palopo</span><span class="detail-link">Lihat Detail <b>→</b></span></div></a>@endforeach</div>@else<div class="empty-panel">Belum ada event terdekat. Silakan kunjungi kembali halaman ini.</div>@endif
    </section>

    <section class="announcement-strip"><div class="announcement-icon">♧</div><div class="announcement-main"><strong>Pengumuman Terbaru</strong>@if($announcements->isNotEmpty())<span>{{ $announcements->first()->title }} · {{ Str::limit(strip_tags($announcements->first()->body),120) }}</span>@else<span>Informasi penting dan terbaru seputar festival & event budaya Kota Palopo.</span>@endif</div><a href="{{ route('announcements') }}">Lihat semua <span>→</span></a></section>
</div>
@endsection
