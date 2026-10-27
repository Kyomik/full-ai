@extends('layouts.portal')
@section('title', $article->title.' — Sistem Informasi Budaya Kota Palopo')
@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>›</span><a href="{{ route('news.index') }}">Berita</a><span>›</span>{{ $article->title }}</div>
    <article class="detail-layout">
        <div class="detail-copy">
            <p class="eyebrow">BERITA · {{ $article->published_at->translatedFormat('d F Y') }}</p>
            <h1>{{ $article->title }}</h1>
            @if($article->image)<img class="detail-poster" src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->title }}">@endif
            @if($article->excerpt)<p class="detail-lead">{{ $article->excerpt }}</p>@endif
            <div class="rich-copy">{!! \App\Support\RichText::sanitize($article->body) !!}</div>
        </div>
        <aside class="content-sidebar"><section class="sidebar-box"><h2>Berita lainnya</h2>@forelse($related as $item)<a class="popular-item" href="{{ route('news.show',$item) }}"><span><strong>{{ $item->title }}</strong><small>{{ $item->published_at->translatedFormat('d F Y') }}</small></span></a>@empty<p>Belum ada berita lain.</p>@endforelse@if($related->isNotEmpty())<a class="sidebar-all" href="{{ route('news.index') }}">Lihat semua berita →</a>@endif</section></aside>
    </article>
</div>
@endsection
