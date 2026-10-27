@extends('layouts.portal')

@section('title', 'Dokumentasi Foto & Video — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a><span>›</span>
        <a href="{{ route('gallery') }}">Galeri</a><span>›</span>
        Dokumentasi
    </div>

    <header class="page-heading">
        <div>
            <h1>Dokumentasi Foto &amp; Video</h1>
            <p>Jelajahi dokumentasi terbaru dari festival dan event budaya Kota Palopo.</p>
        </div>
        <form class="compact-search" action="{{ route('gallery.items') }}" method="GET">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input name="search" value="{{ request('search') }}" placeholder="Cari foto atau video...">
            <button class="solid-button" type="submit">Cari</button>
        </form>
    </header>

    <nav class="gallery-tabs" aria-label="Kategori galeri">
        <a class="{{ !request('category') ? 'active' : '' }}" href="{{ route('gallery.items', array_filter(['search' => request('search')])) }}">Semua</a>
        @foreach($categories as $category)
            <a class="{{ request('category') == $category->id ? 'active' : '' }}" href="{{ route('gallery.items', array_filter(['category' => $category->id, 'search' => request('search')])) }}">{{ $category->name }}</a>
        @endforeach
    </nav>

    <section class="gallery-section all-gallery-items" aria-label="Daftar dokumentasi">
        <div class="photo-grid">
            @forelse($items as $item)
                @include('portal.partials.gallery-item', ['item' => $item, 'showEvent' => true])
            @empty
                <div class="empty-panel">Tidak ada dokumentasi yang cocok dengan pencarian ini.</div>
            @endforelse
        </div>
        {{ $items->links('portal.pagination') }}
    </section>
</div>
@endsection
