<x-filament-panels::page class="cultural-events-page">
    <x-resource-filter-panel class="cultural-events-filter-grid">
            <div class="resource-filter-field cultural-events-filter-search">
                <label for="cultural-event-search">Cari Event</label>
                <input id="cultural-event-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari judul event...">
            </div>

            <div class="resource-filter-field">
                <label for="cultural-event-category">Kategori</label>
                <select id="cultural-event-category" wire:model.live="categoryFilter">
                    <option value="">Semua Kategori</option>
                    @foreach ($this->getCategoryOptions() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @if (auth()->user()?->role === 'super_admin')
                <div class="resource-filter-field">
                    <label for="cultural-event-agency">Instansi</label>
                    <select id="cultural-event-agency" wire:model.live="agencyFilter">
                        <option value="">Semua Instansi</option>
                        @foreach ($this->getAgencyOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="resource-filter-field">
                <label for="cultural-event-status">Status</label>
                <select id="cultural-event-status" wire:model.live="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="approved">Dipublikasikan</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div class="resource-filter-field cultural-events-filter-date">
                <label>Tanggal Event</label>
                <div class="resource-filter-date-fields">
                    <input type="date" aria-label="Tanggal mulai" wire:model.live="dateFrom">
                    <span>-</span>
                    <input type="date" aria-label="Tanggal akhir" wire:model.live="dateUntil">
                </div>
            </div>

            <button type="button" class="resource-filter-reset" wire:click="resetEventFilters">
                <x-filament::icon icon="heroicon-o-arrow-path" />
                Reset Filter
            </button>
    </x-resource-filter-panel>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .cultural-events-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .cultural-events-filter-grid { grid-template-columns: repeat(6, minmax(0, 1fr)) auto; }
                .cultural-events-filter-search { grid-column: span 1; }
                .cultural-events-filter-date { grid-column: span 2; }
            }
            @media (max-width: 1023px) {
                .cultural-events-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .cultural-events-filter-search, .cultural-events-filter-date { grid-column: 1 / -1; }
                .cultural-events-page .resource-filter-reset { justify-self: start; }
            }
        </style>
    @endonce
</x-filament-panels::page>
