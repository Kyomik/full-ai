@extends('layouts.portal')
@section('title', $event->title.' — Sistem Informasi Budaya Kota Palopo')
@section('content')
<div class="page-container content-page">
    <div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>›</span><a href="{{ route('events.index') }}">Festival & Event</a><span>›</span>{{ $event->title }}</div>
    <div class="page-heading"><div><p class="eyebrow">{{ $event->category->name }} · {{ $event->starts_on->translatedFormat('d F Y') }}</p><h1>{{ $event->title }}</h1><p>{{ $event->theme }}</p></div><a class="outline-button" href="{{ route('calendar',['month'=>$event->starts_on->format('Y-m')]) }}">Lihat kalender</a></div>
    <section class="detail-layout">
        <article class="detail-copy">
            @if($event->poster)<img class="detail-poster" src="{{ asset('storage/'.$event->poster) }}" alt="Poster {{ $event->title }}">@endif
            <h2>Tentang acara</h2><div class="rich-copy">{!! nl2br(e($event->description)) !!}</div>
            @if($event->documents->isNotEmpty())<h2>Dokumen pendukung</h2><ul>@foreach($event->documents as $document)<li><a class="text-link" href="{{ asset('storage/'.$document->file_path) }}" target="_blank" rel="noopener">{{ $document->name }} ↗</a></li>@endforeach</ul>@endif
        </article>
        <aside class="content-sidebar"><section class="sidebar-box"><h2>Informasi acara</h2><h3>{{ $event->venue }}</h3><p>{{ $event->address }}</p><div class="detail-fact"><small>TANGGAL</small>{{ $event->starts_on->translatedFormat('d F Y') }}{{ $event->ends_on->ne($event->starts_on) ? ' — '.$event->ends_on->translatedFormat('d F Y') : '' }}</div>@if($event->starts_at)<div class="detail-fact"><small>WAKTU</small>{{ substr((string) $event->starts_at,0,5) }} WITA</div>@endif<div class="detail-fact"><small>PENYELENGGARA</small>{{ $event->agency->name }}</div>@if($event->latitude && $event->longitude)<a class="text-link" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $event->latitude }}&mlon={{ $event->longitude }}#map=16/{{ $event->latitude }}/{{ $event->longitude }}">Buka peta ↗</a>@endif</section></aside>
    </section>
</div>
@endsection
