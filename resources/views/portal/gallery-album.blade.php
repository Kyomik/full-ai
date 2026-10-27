@extends('layouts.portal')

@section('title', $event->title.' — Galeri Kota Palopo')

@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a><span>›</span>
        <a href="{{ route('gallery') }}">Galeri</a><span>›</span>
        <a href="{{ route('gallery.albums') }}">Semua Album</a><span>›</span> {{ $event->title }}
    </div>

    <header class="page-heading">
        <div>
            <span class="eyebrow">{{ $event->category->name }} · {{ $event->starts_on->translatedFormat('d F Y') }}</span>
            <h1>{{ $event->title }}</h1>
            <p>Dokumentasi foto dan video dari event ini.</p>
        </div>
    </header>

    <div class="photo-grid album-photo-grid">
        @forelse($items as $item)
            @include('portal.partials.gallery-item', ['item' => $item])
        @empty
            <div class="empty-panel">Belum ada foto atau video di album ini.</div>
        @endforelse
    </div>

    {{ $items->links('portal.pagination') }}
</div>
@endsection
