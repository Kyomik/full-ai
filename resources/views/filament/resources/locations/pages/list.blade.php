<x-filament-panels::page class="location-page resource-flat-table">
    <x-resource-filter-toolbar class="location-filter-toolbar">
        <div class="resource-filter-field location-filter-search">
            <label for="location-search">Cari Lokasi</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="location-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari nama lokasi...">
            </div>
        </div>

        <div class="resource-filter-field">
            <label for="location-category">Kategori</label>
            <select id="location-category" wire:model.live="categoryFilter">
                <option value="">Semua Kategori</option>
                @foreach ($this->getCategoryOptions() as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="location-status">Status</label>
            <select id="location-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <button type="button" class="resource-filter-reset" wire:click="resetLocationFilters">
            <x-filament::icon icon="heroicon-o-arrow-path" />
            Reset Filter
        </button>
    </x-resource-filter-toolbar>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .location-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .location-filter-toolbar { grid-template-columns: minmax(12rem, 1.15fr) minmax(10rem, 1fr) minmax(9rem, .9fr) auto; gap: 2rem; }
            }
            @media (max-width: 1023px) {
                .location-filter-toolbar { gap: 1.25rem 1.5rem; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
