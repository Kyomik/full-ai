@extends('layouts.portal')
@section('title', 'Pengumuman — Sistem Informasi Budaya Kota Palopo')
@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>›</span>Pengumuman</div>
    <header class="page-heading"><div><h1>Pengumuman</h1><p>Informasi resmi terkait festival, event, dan layanan budaya Kota Palopo.</p></div></header>
    <section class="announcement-list">
        @forelse($items as $item)
            <article class="announcement-card"><time>{{ $item->published_on?->translatedFormat('d F Y') ?? 'Informasi resmi' }}</time><h2>{{ $item->title }}</h2><div class="rich-copy">{!! nl2br(e(strip_tags($item->body))) !!}</div></article>
        @empty
            <div class="empty-panel">Belum ada pengumuman. Silakan kunjungi kembali halaman ini.</div>
        @endforelse
        {{ $items->links() }}
    </section>
</div>
@endsection
