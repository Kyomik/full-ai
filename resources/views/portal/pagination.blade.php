@if ($paginator->hasPages())
    <nav class="portal-pagination" role="navigation" aria-label="Navigasi halaman">
        <ul>
            @if ($paginator->onFirstPage())
                <li class="is-disabled" aria-disabled="true"><span aria-label="Sebelumnya">‹</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya">‹</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="is-disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li class="is-current" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman selanjutnya">›</a></li>
            @else
                <li class="is-disabled" aria-disabled="true"><span aria-label="Selanjutnya">›</span></li>
            @endif
        </ul>
    </nav>
@endif
