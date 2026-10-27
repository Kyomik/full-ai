@extends('layouts.portal')

@section('title', 'Galeri — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>›</span> Galeri</div>

    <div class="page-heading">
        <div>
            <h1>Galeri</h1>
            <!-- <p>Album dikelompokkan berdasarkan event. Dokumentasi terbaru diurutkan dari waktu unggah.</p> -->
        </div>
        <form class="compact-search" action="{{ route('gallery') }}">
            <input name="search" value="{{ request('search') }}" placeholder="Cari foto atau video...">
            <button class="solid-button">Cari</button>
        </form>
    </div>

    <nav class="gallery-tabs" aria-label="Kategori galeri">
        <a class="{{ !request('category') ? 'active' : '' }}" href="{{ route('gallery') }}">Semua</a>
        @foreach($categories as $category)
            <a class="{{ request('category') == $category->id ? 'active' : '' }}" href="{{ route('gallery', ['category' => $category->id]) }}">{{ $category->name }}</a>
        @endforeach
    </nav>

    <div class="news-layout gallery-layout">
        <main class="gallery-main">
            <section class="gallery-section">
                <div class="section-title-row gallery-section-heading">
                    <div>
                        <h2>Album Terbaru</h2>
                        <p>Diurutkan berdasarkan dokumentasi yang terakhir diunggah.</p>
                    </div>
                    @if($albums->isNotEmpty())<a href="{{ route('gallery.albums') }}">Lihat semua album <span>→</span></a>@endif
                </div>

                <div class="album-grid">
                    @forelse($albums as $album)
                        @include('portal.partials.album-card', ['album' => $album])
                    @empty
                        <div class="empty-panel">Album akan muncul setelah dokumentasi event diterbitkan.</div>
                    @endforelse
                </div>
            </section>

            <section class="gallery-section" id="dokumentasi-terbaru">
                <div class="section-title-row gallery-section-heading recent-title">
                    <div>
                        <h2>Foto Terbaru</h2>
                        <p>Foto dan video yang paling baru ditambahkan.</p>
                    </div>
                    @if($items->isNotEmpty())<a href="{{ route('gallery.items') }}">Lihat semua dokumentasi <span>→</span></a>@endif
                </div>

                <div class="photo-grid">
                    @forelse($items as $item)
                        @include('portal.partials.gallery-item', ['item' => $item, 'showEvent' => true])
                    @empty
                        <div class="empty-panel">Belum ada foto atau video yang diterbitkan.</div>
                    @endforelse
                </div>
            </section>
        </main>

        <aside class="content-sidebar gallery-sidebar">
            <section class="sidebar-box gallery-category-sidebar">
                <h2>Kategori Galeri</h2>
                <a class="category-side-item" href="{{ route('gallery') }}">Semua <span>{{ $categories->sum('photos_count') }}</span></a>
                @foreach($categories as $category)
                    <a class="category-side-item" href="{{ route('gallery', ['category' => $category->id]) }}">{{ $category->name }} <span>{{ $category->photos_count }}</span></a>
                @endforeach
            </section>

            <section class="sidebar-box">
                <h2>Galeri Populer</h2>
                @forelse($popularAlbums as $album)
                    @php($coverMedia = $album->latestPublishedGalleryItem)
                    <a class="popular-item" href="{{ route('gallery.album', $album) }}">
                        <span class="popular-thumb">
                            @if($coverMedia)
                                <img src="{{ asset('storage/'.$coverMedia->file_path) }}" alt="" loading="lazy">
                            @else
                                <span aria-hidden="true">â–§</span>
                            @endif
                        </span>
                        <span>
                            <strong>{{ $album->title }}</strong>
                            <small>{{ number_format($album->gallery_views_count) }} akses · {{ $album->media_count }} foto/video</small>
                        </span>
                    </a>
                @empty
                    <p>Album populer akan tampil di sini.</p>
                @endforelse
            </section>
        </aside>
    </div>
</div>
@endsection
