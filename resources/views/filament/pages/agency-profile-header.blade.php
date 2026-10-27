<x-resource-page-header
    :heading="$this->getTitle()"
    :subheading="$this->getSubheading()"
    :breadcrumbs="$this->getBreadcrumbs()"
    :actions="[]"
>
    <x-slot name="actions">
        <x-filament::button color="gray" icon="heroicon-o-clock" type="button" x-on:click="$dispatch('open-modal', { id: 'agency-profile-history' })">
            Riwayat Perubahan
        </x-filament::button>
    </x-slot>
</x-resource-page-header>

<x-filament::modal id="agency-profile-history" icon="heroicon-o-clock" icon-color="gray" width="md">
    <x-slot name="heading">Riwayat Perubahan</x-slot>

    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
        <div class="flex items-start justify-between gap-4 border-b border-gray-200 pb-3 dark:border-gray-700">
            <span>Profil dibuat</span>
            <strong class="text-right text-gray-950 dark:text-white">{{ auth()->user()->agency?->created_at?->translatedFormat('d M Y, H:i') ?? '—' }}</strong>
        </div>
        <div class="flex items-start justify-between gap-4">
            <span>Terakhir diperbarui</span>
            <strong class="text-right text-gray-950 dark:text-white">{{ auth()->user()->agency?->updated_at?->translatedFormat('d M Y, H:i') ?? '—' }}</strong>
        </div>
    </div>
</x-filament::modal>
