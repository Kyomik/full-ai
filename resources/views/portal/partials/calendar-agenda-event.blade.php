<a class="calendar-agenda-event" href="{{ route('events.show', $event) }}">
    <span class="calendar-agenda-time">
        {{ $event->starts_at ? substr((string) $event->starts_at, 0, 5).' WITA' : 'Sepanjang hari' }}
    </span>
    <span class="calendar-agenda-copy">
        <strong>{{ $event->title }}</strong>
        <small>{{ $event->venue }}@if($event->address), {{ $event->address }}@endif</small>
    </span>
    <span class="calendar-agenda-category">{{ $event->category->name }}</span>
</a>
