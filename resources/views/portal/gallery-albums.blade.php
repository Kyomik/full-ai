@extends('layouts.portal')

@section('title', 'Album Galeri — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Beranda</a><span>›</span>
        <a href="{{ route('gallery') }}">Galeri</a><span>›</span> Semua Album
    </div>

    <header class="page-heading">
        <div>
            <h1>Semua Album</h1>
            <p>Setiap album berisi dokumentasi dari satu festival atau event.</p>
        </div>
    </header>

    <div class="album-grid album-grid-all">
        @forelse($albums as $album)
            @include('portal.partials.album-card', ['album' => $album])
        @empty
            <div class="empty-panel">Belum ada album dokumentasi yang diterbitkan.</div>
        @endforelse
    </div>

    {{ $albums->links('portal.pagination') }}
</div>
@endsection
