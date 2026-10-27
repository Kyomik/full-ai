@php
    $roleClass = auth()->user()?->role === 'super_admin' ? 'super-admin-overview' : 'agency-admin-overview';
@endphp

<x-filament-widgets::widget :class="$roleClass">
    <div
        @if ($pollingInterval = $this->getPollingInterval())
            wire:poll.{{ $pollingInterval }}
        @endif
        class="fi-wi-stats-overview-stats-ctn grid gap-6"
    >
        @foreach ($this->getCachedStats() as $stat)
            {{ $stat }}
        @endforeach
    </div>
</x-filament-widgets::widget>
