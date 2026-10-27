<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Event Mendatang</x-slot>
        <x-slot name="headerEnd">
            <a class="text-xs font-medium text-gray-600 hover:text-gray-950 dark:text-gray-400 dark:hover:text-white" href="{{ $allEventsUrl }}" wire:navigate>Lihat Semua →</a>
        </x-slot>

        <div class="divide-y divide-gray-200 dark:divide-white/10">
            @forelse ($events as $event)
                <a href="{{ route('events.show', $event) }}" class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                    <span class="grid min-h-11 w-11 shrink-0 content-center justify-items-center rounded border border-gray-300 px-1 py-1.5 text-gray-900 dark:border-gray-600 dark:text-white">
                        <strong class="text-sm leading-none">{{ $event->starts_on?->format('d') }}</strong>
                        <small class="mt-1 text-[9px] uppercase">{{ $event->starts_on?->translatedFormat('M') }}</small>
                    </span>
                    <span class="min-w-0 flex-1">
                        <strong class="block truncate text-sm font-semibold text-gray-950 dark:text-white">{{ $event->title }}</strong>
                        <small class="mt-1 block truncate text-xs text-gray-500 dark:text-gray-400">
                            {{ $event->starts_on?->translatedFormat('d M Y') }}{{ $event->venue ? ' · ' . $event->venue : '' }}
                        </small>
                    </span>
                </a>
            @empty
                <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada event mendatang.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
