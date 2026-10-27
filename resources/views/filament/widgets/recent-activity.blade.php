<x-filament-widgets::widget>
    <x-filament::section heading="Aktivitas Terbaru">
        <x-slot name="headerEnd">
            <a class="text-xs font-medium text-gray-600 hover:text-gray-950 dark:text-gray-400 dark:hover:text-white" href="{{ $allActivitiesUrl }}" wire:navigate>Lihat Semua →</a>
        </x-slot>

        <div class="dashboard-activity-timeline">
            <span class="dashboard-activity-timeline-line" aria-hidden="true"></span>
            @forelse ($activities as $activity)
                <div class="dashboard-activity-timeline-item">
                    <span class="dashboard-activity-timeline-dot" aria-hidden="true"></span>
                    <p class="text-sm text-gray-950 dark:text-white">{{ $activity['title'] }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $activity['date']->format('d M Y H:i') }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
