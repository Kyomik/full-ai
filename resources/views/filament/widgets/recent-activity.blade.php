<x-filament-widgets::widget>
    <x-filament::section heading="Aktivitas Terbaru">
        <div class="space-y-4">
            @forelse ($activities as $activity)
                <div class="flex gap-3">
                    <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-gray-900 dark:bg-gray-200"></span>
                    <div class="min-w-0">
                        <p class="text-sm text-gray-950 dark:text-white">{{ $activity['title'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $activity['date']->format('d M Y H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
