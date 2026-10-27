<x-filament-panels::page class="gallery-page resource-flat-table">
    <x-resource-filter-panel class="gallery-filter-grid">
        <div class="resource-filter-field gallery-filter-search">
            <label for="gallery-search">Cari Galeri</label>
            <input id="gallery-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari judul galeri...">
        </div>

        <div class="resource-filter-field">
            <label for="gallery-category">Kategori</label>
            <select id="gallery-category" wire:model.live="categoryFilter">
                <option value="">Semua Kategori</option>
                @foreach ($this->getCategoryOptions() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="gallery-type">Jenis</label>
            <select id="gallery-type" wire:model.live="typeFilter">
                <option value="">Semua Jenis</option>
                <option value="photo">Foto</option>
                <option value="video">Video</option>
            </select>
        </div>

        <div class="resource-filter-field gallery-filter-date">
            <label>Tanggal Unggah</label>
            <x-resource-filter-date-range from="uploadedFrom" until="uploadedUntil" from-label="Tanggal unggah awal" until-label="Tanggal unggah akhir" />
        </div>

        <x-resource-filter-reset action="resetGalleryFilters" />
    </x-resource-filter-panel>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .gallery-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .gallery-filter-grid { grid-template-columns: minmax(9rem, 1.15fr) minmax(8rem, 1fr) minmax(7rem, .8fr) minmax(13rem, 1.45fr) auto; gap: 1.25rem; }
            }
            @media (max-width: 1023px) {
                .gallery-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .gallery-filter-search, .gallery-filter-date { grid-column: 1 / -1; }
                .gallery-page .resource-filter-reset { justify-self: start; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
