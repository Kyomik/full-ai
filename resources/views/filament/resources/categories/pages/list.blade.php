<x-filament-panels::page class="category-page resource-flat-table">
    <x-resource-filter-toolbar class="category-filter-toolbar">
        <div class="resource-filter-field category-filter-search">
            <label for="category-search">Cari Kategori</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="category-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari nama kategori...">
            </div>
        </div>

        <div class="resource-filter-field category-filter-status">
            <label for="category-status">Status</label>
            <select id="category-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <button type="button" class="resource-filter-reset" wire:click="resetCategoryFilters">
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
            .category-filter-toolbar { grid-template-columns: minmax(14rem, 1fr) minmax(12rem, .8fr) auto; gap: 3rem; }
            @media (max-width: 1023px) {
                .category-filter-toolbar { gap: 1.25rem 1.5rem; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
