@php($record = $getRecord())

<div class="gallery-thumbnail">
    @if ($record->type === 'video')
        <x-filament::icon icon="heroicon-o-video-camera" />
    @elseif ($record->file_path)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($record->file_path) }}" alt="{{ $record->title }}">
    @else
        <x-filament::icon icon="heroicon-o-photo" />
    @endif
</div>

@once
    <style>
        .gallery-thumbnail { display: grid; width: 3.5rem; height: 3.5rem; place-items: center; overflow: hidden; border: 1px solid rgb(209 213 219); background: rgb(249 250 251); color: rgb(107 114 128); }
        .gallery-thumbnail img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-thumbnail svg { width: 1.5rem; height: 1.5rem; }
        .dark .gallery-thumbnail { border-color: rgb(75 85 99); background: rgb(31 41 55); color: rgb(156 163 175); }
    </style>
@endonce
