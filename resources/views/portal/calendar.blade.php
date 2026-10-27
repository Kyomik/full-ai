@extends('layouts.portal')

@section('title', 'Kalender Event — Sistem Informasi Budaya Kota Palopo')

@section('content')
<div class="page-container content-page calendar-page">
    @php
        $previousUrl = $view === 'month'
            ? route('calendar', ['view' => 'month', 'month' => $previousPeriod->format('Y-m')])
            : route('calendar', ['view' => $view, 'date' => $previousPeriod->format('Y-m-d')]);
        $nextUrl = $view === 'month'
            ? route('calendar', ['view' => 'month', 'month' => $nextPeriod->format('Y-m')])
            : route('calendar', ['view' => $view, 'date' => $nextPeriod->format('Y-m-d')]);
        $todayUrl = route('calendar', ['view' => $view, 'date' => now()->format('Y-m-d')]);
        $monthUrl = route('calendar', ['view' => 'month', 'month' => $selectedDate->format('Y-m')]);
        $weekUrl = route('calendar', ['view' => 'week', 'date' => $selectedDate->format('Y-m-d')]);
        $dayUrl = route('calendar', ['view' => 'day', 'date' => $selectedDate->format('Y-m-d')]);
        $periodLabel = match ($view) {
            'week' => $selectedDate->copy()->startOfWeek()->translatedFormat('d M') . ' – ' . $selectedDate->copy()->endOfWeek()->translatedFormat('d M Y'),
            'day' => $selectedDate->translatedFormat('l, d F Y'),
            default => $month->translatedFormat('F Y'),
        };
    @endphp

    <div class="breadcrumbs"><a href="{{ route('home') }}">Beranda</a><span>›</span> Kalender Event</div>

    <div class="page-heading">
        <div>
            <h1>Kalender Event</h1>
            <p>Lihat jadwal festival dan event budaya di Kota Palopo berdasarkan tanggal.</p>
        </div>
        <form class="compact-search" action="{{ route('events.index') }}">
            <input name="search" placeholder="Cari event...">
            <button class="solid-button">Cari</button>
        </form>
    </div>

    <div class="calendar-layout">
        <section class="calendar-main" aria-label="Kalender {{ $view === 'month' ? 'bulanan' : ($view === 'week' ? 'mingguan' : 'harian') }}">
            <div class="calendar-toolbar">
                <a class="outline-button" href="{{ $todayUrl }}">Hari Ini</a>
                <div class="month-arrows">
                    <a class="outline-button" aria-label="Periode sebelumnya" href="{{ $previousUrl }}">‹</a>
                    <a class="outline-button" aria-label="Periode berikutnya" href="{{ $nextUrl }}">›</a>
                </div>
                <strong class="calendar-period-label">{{ $periodLabel }}</strong>
                <div class="view-switch">
                    <a class="{{ $view === 'month' ? 'active' : '' }}" href="{{ $monthUrl }}" @if($view === 'month') aria-current="page" @endif>▦ Bulan</a>
                    <a class="{{ $view === 'week' ? 'active' : '' }}" href="{{ $weekUrl }}" @if($view === 'week') aria-current="page" @endif>▤ Minggu</a>
                    <a class="{{ $view === 'day' ? 'active' : '' }}" href="{{ $dayUrl }}" @if($view === 'day') aria-current="page" @endif>◷ Hari</a>
                </div>
            </div>

            @if($view === 'month')
            <div class="month-grid">
                <div class="weekday-row">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $weekday)
                        <span>{{ $weekday }}</span>
                    @endforeach
                </div>

                @foreach($days as $day)
                    @php($dayKey = $day['date']->format('Ymd'))
                    <div class="month-day {{ $day['date']->month === $month->month ? '' : 'outside-month' }} {{ $day['date']->isToday() ? 'today' : '' }} {{ $loop->index % 7 >= 3 ? 'calendar-popover-align-right' : '' }}">
                        <span class="day-number">{{ $day['date']->day }}</span>

                        @foreach($day['events']->take(2) as $event)
                            <a class="calendar-event-chip" href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                        @endforeach

                        @if($day['events']->count() > 2)
                            <button
                                class="more-events calendar-more-toggle"
                                type="button"
                                aria-expanded="false"
                                aria-controls="calendar-events-{{ $dayKey }}"
                                data-calendar-more-toggle
                            >+{{ $day['events']->count() - 2 }} lainnya</button>

                            <div class="calendar-event-popover" id="calendar-events-{{ $dayKey }}" role="region" aria-label="Event lain pada {{ $day['date']->translatedFormat('d F Y') }}" hidden>
                                <div class="calendar-event-popover-heading">
                                    <strong>Event lain</strong>
                                    <span>{{ $day['date']->translatedFormat('d F Y') }}</span>
                                    <button type="button" aria-label="Tutup daftar event" data-calendar-popover-close>×</button>
                                </div>
                                <div class="calendar-event-popover-list">
                                    @foreach($day['events']->slice(2) as $event)
                                        <a class="calendar-event-popover-item" href="{{ route('events.show', $event) }}">
                                            <strong>{{ $event->title }}</strong>
                                            <span>
                                                @if($event->starts_at){{ substr((string) $event->starts_at, 0, 5) }} WITA · @endif
                                                {{ $event->venue }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @else
                <div class="calendar-agenda-view calendar-{{ $view }}-view">
                    @foreach($days as $day)
                        <section class="calendar-agenda-day {{ $day['date']->isToday() ? 'today' : '' }}">
                            <h2>
                                <span>{{ $day['date']->translatedFormat($view === 'week' ? 'l' : 'l, d F Y') }}</span>
                                @if($view === 'week')<strong>{{ $day['date']->translatedFormat('d F') }}</strong>@endif
                            </h2>
                            <div class="calendar-agenda-events">
                                @forelse($day['events'] as $event)
                                    @include('portal.partials.calendar-agenda-event', ['event' => $event])
                                @empty
                                    <p class="calendar-agenda-empty">Tidak ada event terjadwal.</p>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="content-sidebar calendar-sidebar">
            <section class="sidebar-box">
                <h2>Event Mendatang</h2>
                @forelse($events->take(5) as $event)
                    <a class="upcoming-item" href="{{ route('events.show', $event) }}">
                        <span class="upcoming-date">{{ $event->starts_on->format('d') }}<small>{{ strtoupper($event->starts_on->translatedFormat('M')) }}</small></span>
                        <span>
                            <strong>{{ $event->title }}</strong>
                            <small>▦ {{ $event->starts_on->translatedFormat('d F Y') }}</small>
                            <small>⌖ {{ $event->venue }}</small>
                        </span>
                    </a>
                @empty
                    <p>Belum ada event di bulan ini.</p>
                @endforelse
                @if($events->isNotEmpty())<a class="sidebar-all" href="{{ route('events.index') }}">Lihat semua event →</a>@endif
            </section>

            <section class="sidebar-box">
                <h2>Kategori Event</h2>
                @foreach($events->pluck('category')->unique('id')->take(6) as $category)
                    <a class="category-side-item" href="{{ route('events.index', ['category' => $category->id]) }}">◉ {{ $category->name }}<span>›</span></a>
                @endforeach
            </section>
        </aside>
    </div>
</div>
@endsection
