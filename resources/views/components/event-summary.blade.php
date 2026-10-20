@props([
    'record',
    'showCategory' => false,
])

<div class="event-summary">
    <div class="event-summary-poster">
        @if ($record->poster)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($record->poster) }}" alt="Poster {{ $record->title }}">
        @else
            <x-filament::icon icon="heroicon-o-photo" />
        @endif
    </div>
    <div class="event-summary-copy">
        <div class="event-summary-title">{{ $record->title }}</div>
        @if ($showCategory && $record->category?->name)
            <div class="event-summary-category">{{ $record->category->name }}</div>
        @endif
        <div class="event-summary-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($record->description), 84) }}</div>
    </div>
</div>

@once
    <style>
        .event-summary { display: flex; min-width: 16rem; align-items: center; gap: .75rem; }
        .event-summary-poster { display: grid; width: 3.25rem; height: 3.25rem; flex: none; place-items: center; overflow: hidden; border: 1px solid rgb(209 213 219); border-radius: .25rem; background: rgb(249 250 251); color: rgb(107 114 128); }
        .event-summary-poster img { width: 100%; height: 100%; object-fit: cover; }
        .event-summary-poster svg { width: 1.5rem; height: 1.5rem; }
        .event-summary-copy { min-width: 0; }
        .event-summary-title { overflow: hidden; color: rgb(17 24 39); font-size: .8125rem; font-weight: 600; line-height: 1.25rem; text-overflow: ellipsis; white-space: nowrap; }
        .event-summary-category { overflow: hidden; margin-top: .125rem; color: rgb(22 101 52); font-size: .6875rem; font-weight: 600; line-height: 1rem; text-overflow: ellipsis; white-space: nowrap; }
        .event-summary-excerpt { display: -webkit-box; overflow: hidden; color: rgb(107 114 128); font-size: .6875rem; line-height: 1rem; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        .dark .event-summary-poster { border-color: rgb(75 85 99); background: rgb(31 41 55); }
        .dark .event-summary-title { color: rgb(255 255 255); }
        .dark .event-summary-category { color: rgb(134 239 172); }
        .dark .event-summary-excerpt { color: rgb(156 163 175); }
    </style>
@endonce
