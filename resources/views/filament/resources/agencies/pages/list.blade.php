<x-filament-panels::page class="agency-page resource-flat-table">
    <x-resource-filter-toolbar class="agency-filter-toolbar">
        <div class="resource-filter-field agency-filter-search">
            <label for="agency-search">Cari Instansi</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="agency-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari nama instansi...">
            </div>
        </div>

        <div class="resource-filter-field">
            <label for="agency-type">Jenis Instansi</label>
            <select id="agency-type" wire:model.live="typeFilter">
                <option value="">Semua Jenis</option>
                @foreach ($this->getAgencyTypes() as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="agency-status">Status</label>
            <select id="agency-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <x-resource-filter-reset action="resetAgencyFilters" />
    </x-resource-filter-toolbar>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .agency-filter-toolbar { grid-template-columns: minmax(12rem, 1.35fr) minmax(10rem, 1.15fr) minmax(9rem, 1fr) auto; gap: 2rem; }
            .agency-page .agency-name-column { width: 30% !important; min-width: 20rem; }
            .agency-page .agency-email-column { width: 12% !important; max-width: 12rem; }
            @media (max-width: 1023px) {
                .agency-filter-toolbar { gap: 1.25rem 1.5rem; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
