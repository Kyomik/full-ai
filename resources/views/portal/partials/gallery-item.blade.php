<figure class="gallery-photo {{ $item->type === 'video' ? 'gallery-video' : '' }}">
    @if($item->type === 'video')
        <video controls preload="metadata" aria-label="Video {{ $item->title }}">
            <source src="{{ asset('storage/'.$item->file_path) }}">
            Browser Anda tidak mendukung pemutaran video.
        </video>
    @else
        <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->title }}" loading="lazy">
    @endif
    <figcaption>
        {{ $item->title }}
        @if(($showEvent ?? false) && $item->event)
            <small>{{ $item->event->title }}</small>
        @endif
    </figcaption>
</figure>
