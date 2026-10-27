<x-filament-panels::page class="article-page resource-flat-table">
    <x-resource-filter-panel class="article-filter-grid">
        <div class="resource-filter-field article-filter-search">
            <label for="article-search">Cari Berita</label>
            <input id="article-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari judul berita...">
        </div>

        <div class="resource-filter-field">
            <label for="article-status">Status</label>
            <select id="article-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <div class="resource-filter-field article-filter-date">
            <label>Tanggal Publikasi</label>
            <x-resource-filter-date-range from="publishedFrom" until="publishedUntil" from-label="Tanggal publikasi awal" until-label="Tanggal publikasi akhir" />
        </div>

        <x-resource-filter-reset action="resetArticleFilters" />
    </x-resource-filter-panel>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .article-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .article-filter-grid { grid-template-columns: minmax(10rem, 1.1fr) minmax(9rem, .9fr) minmax(13rem, 1.3fr) auto; gap: 1.5rem; }
            }
            @media (max-width: 1023px) {
                .article-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .article-filter-search, .article-filter-date { grid-column: 1 / -1; }
                .article-page .resource-filter-reset { justify-self: start; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
