<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-megaphone" icon-color="gray">
        <x-slot name="heading">Pengumuman Terbaru</x-slot>
        <x-slot name="headerEnd">
            <a class="text-xs font-medium text-gray-600 hover:text-gray-950 dark:text-gray-400 dark:hover:text-white" href="{{ $allAnnouncementsUrl }}" wire:navigate>Lihat Semua →</a>
        </x-slot>

        <div class="divide-y divide-gray-200 dark:divide-white/10">
            @forelse ($announcements as $announcement)
                <article class="py-3 first:pt-0 last:pb-0">
                    @php($data = $announcement->data ?? [])
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-sm font-semibold text-gray-950 dark:text-white">{{ $data['title'] ?? 'Pengumuman' }}</h3>
                        <time class="shrink-0 text-[11px] text-gray-500 dark:text-gray-400">{{ $announcement->created_at?->translatedFormat('d M Y') }}</time>
                    </div>
                    <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-gray-600 dark:text-gray-300">{{ \Illuminate\Support\Str::limit($data['message'] ?? '', 180) }}</p>
                </article>
            @empty
                <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada pengumuman terbaru.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
