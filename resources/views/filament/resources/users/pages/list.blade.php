<x-filament-panels::page class="users-page resource-flat-table">
    <x-resource-filter-toolbar class="users-filter-toolbar">
        <div class="resource-filter-field users-filter-search">
            <label for="user-search">Cari Pengguna</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="user-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari nama, username, atau email...">
            </div>
        </div>

        <div class="resource-filter-field">
            <label for="user-role">Peran</label>
            <select id="user-role" wire:model.live="roleFilter">
                <option value="">Semua Peran</option>
                <option value="super_admin">Super Admin</option>
                <option value="agency_admin">Admin Instansi</option>
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="user-status">Status</label>
            <select id="user-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>

        <x-resource-filter-reset action="resetUserFilters" />
    </x-resource-filter-toolbar>

    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs />
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}
        {{ $this->table }}
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>

    @once
        <style>
            .users-page .user-edit-action { color: rgb(217 119 6) !important; }
            .users-page .user-edit-action:hover { color: rgb(180 83 9) !important; }
            .users-filter-toolbar { grid-template-columns: minmax(16rem, 1fr) minmax(11rem, .75fr) minmax(11rem, .75fr) auto; gap: 3rem; }
            @media (max-width: 1023px) {
                .users-filter-toolbar { gap: 1.25rem 1.5rem; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
