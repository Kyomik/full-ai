@php
    $previewItems = $album->previewGalleryItems ?? collect();
    $coverMedia = $previewItems->get(0);
    $middleMedia = $previewItems->get(1);
    $backMedia = $previewItems->get(2);
    $isVideo = fn ($media) => $media && ($media->type === 'video' || in_array(strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION)), ['mp4', 'webm', 'mov', 'm4v'], true));
@endphp

<a class="album-card" href="{{ route('gallery.album', $album->slug) }}">
    <div class="album-stack-wrap">
        @if($backMedia)
            <span class="album-stack-layer album-stack-back" aria-hidden="true">
                @if(! $isVideo($backMedia))<img src="{{ asset('storage/'.$backMedia->file_path) }}" alt="">@else<span class="album-stack-video-mark">▶</span>@endif
            </span>
        @endif
        @if($middleMedia)
            <span class="album-stack-layer album-stack-middle" aria-hidden="true">
                @if(! $isVideo($middleMedia))<img src="{{ asset('storage/'.$middleMedia->file_path) }}" alt="">@else<span class="album-stack-video-mark">▶</span>@endif
            </span>
        @endif

        <div class="album-cover">
            @if($isVideo($coverMedia))
                <span class="album-video-cover" aria-label="Album video">▶ Video</span>
            @elseif($coverMedia)
                <img src="{{ asset('storage/'.$coverMedia->file_path) }}" alt="Dokumentasi {{ $album->title }}" loading="lazy">
            @else
                <span aria-hidden="true">▧</span>
            @endif
            <div class="album-cover-caption">
                <strong>{{ $album->title }}</strong>
                <span>{{ $album->starts_on->translatedFormat('d M Y') }}</span>
                <small>{{ $album->media_count }} dokumentasi</small>
            </div>
        </div>
    </div>
</a>
