<x-filament-panels::page class="notification-page resource-flat-table">
    <x-resource-filter-toolbar class="notification-filter-toolbar">
        <div class="resource-filter-field notification-filter-search">
            <label for="notification-search">Cari Notifikasi</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="notification-search" type="search" wire:model.live.debounce.400ms="search" placeholder="Cari judul notifikasi...">
            </div>
        </div>

        <div class="resource-filter-field">
            <label for="notification-type">Jenis Notifikasi</label>
            <select id="notification-type" wire:model.live="typeFilter">
                <option value="">Semua Jenis</option>
                <option value="information">Informasi</option>
                <option value="announcement">Pengumuman</option>
                <option value="system">Sistem</option>
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="notification-status">Status</label>
            <select id="notification-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="sent">Terkirim</option>
                <option value="unread">Belum Dibaca</option>
                <option value="read">Sudah Dibaca</option>
            </select>
        </div>

        <button type="button" class="resource-filter-reset" wire:click="resetFilters">
            <x-filament::icon icon="heroicon-o-arrow-path" />
            Reset Filter
        </button>
    </x-resource-filter-toolbar>

    <section class="notification-table-wrap" aria-label="Daftar notifikasi">
        <div class="overflow-x-auto">
            <table class="notification-table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Judul Notifikasi</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Isi Notifikasi</th>
                        <th scope="col">Tanggal Kirim</th>
                        <th scope="col">Status</th>
                        <th scope="col">Dikirim Oleh</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $notification)
                        @php($data = $notification->data ?? [])
                        <tr wire:key="notification-{{ $notification->id }}">
                            <td class="notification-number">{{ $notifications->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="notification-title-cell">
                                    <x-filament::icon icon="heroicon-o-bell" />
                                    <div>
                                        <strong>{{ $data['title'] ?? 'Pemberitahuan' }}</strong>
                                        <p>{{ $data['message'] ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $this->notificationTypeLabel($data) }}</td>
                            <td class="notification-message">{{ $data['message'] ?? '—' }}</td>
                            <td class="notification-date">{{ $notification->created_at?->translatedFormat('d M Y, H:i') ?? '—' }} WIB</td>
                            <td>
                                <span @class(['notification-status', 'is-unread' => is_null($notification->read_at) && empty($data['sender_name']), 'is-sent' => ! empty($data['sender_name'])])>
                                    {{ ! empty($data['sender_name']) ? 'Terkirim' : ($notification->read_at ? 'Sudah Dibaca' : 'Belum Dibaca') }}
                                </span>
                            </td>
                            <td>{{ $data['sender_name'] ?? 'Sistem' }}</td>
                            <td class="text-right">
                                @if (! empty($data['event_id']))
                                    <button type="button" class="notification-view-action" wire:click="viewNotification('{{ $notification->id }}')">
                                        <x-filament::icon icon="heroicon-o-eye" />
                                        <span>Lihat</span>
                                    </button>
                                @else
                                    <button type="button" class="notification-view-action" wire:click="viewNotification('{{ $notification->id }}')" title="Tandai notifikasi telah dibaca">
                                        <x-filament::icon icon="heroicon-o-eye" />
                                        <span>Lihat</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="notification-empty">Tidak ada notifikasi yang sesuai dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="notification-table-footer">
            <p>
                @if ($notifications->total() > 0)
                    Menampilkan {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} dari {{ $notifications->total() }} data
                @else
                    Menampilkan 0 data
                @endif
            </p>
            {{ $notifications->links() }}
        </footer>
    </section>

    @once
        <style>
            .notification-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .notification-filter-toolbar { grid-template-columns: minmax(14rem, 1.2fr) minmax(10rem, .9fr) minmax(9rem, .8fr) auto; gap: 2rem; }
            }
            @media (max-width: 1023px) {
                .notification-filter-toolbar { gap: 1.25rem 1.5rem; }
            }
            .notification-table-wrap { overflow: hidden; border: 0; background: transparent; }
            .notification-table { width: 100%; min-width: 68rem; border-collapse: collapse; text-align: left; color: rgb(17 24 39); font-size: .75rem; }
            .notification-table th { padding: .875rem .5rem; border-bottom: 1px solid rgb(156 163 175); font-weight: 700; white-space: nowrap; }
            .notification-table td { padding: .875rem .5rem; border-bottom: 1px solid rgb(209 213 219); vertical-align: middle; }
            .notification-number { width: 3rem; }
            .notification-title-cell { display: flex; min-width: 13rem; align-items: flex-start; gap: .75rem; }
            .notification-title-cell > svg { width: 1.25rem; height: 1.25rem; flex: none; margin-top: .1rem; }
            .notification-title-cell strong { display: block; font-weight: 700; }
            .notification-title-cell p { max-width: 17rem; margin: .25rem 0 0; color: rgb(75 85 99); line-height: 1.5; }
            .notification-message { max-width: 16rem; line-height: 1.55; }
            .notification-date { min-width: 7rem; white-space: nowrap; }
            .notification-status { display: inline-flex; border: 1px solid rgb(156 163 175); border-radius: .25rem; padding: .2rem .45rem; font-size: .625rem; white-space: nowrap; }
            .notification-status.is-unread { border-color: rgb(21 128 61); color: rgb(21 128 61); }
            .notification-status.is-sent { border-color: rgb(156 163 175); color: rgb(31 41 55); }
            .notification-view-action { border: 0; background: transparent; padding: 0; cursor: pointer; display: inline-flex; flex-direction: column; align-items: center; gap: .125rem; color: rgb(17 24 39); text-decoration: none; }
            .notification-view-action svg { width: 1.125rem; height: 1.125rem; }
            .notification-view-action span { font-size: .625rem; }
            .notification-empty { padding: 3rem 1rem !important; text-align: center; color: rgb(107 114 128); }
            .notification-table-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding-top: 1rem; color: rgb(75 85 99); font-size: .75rem; }
            .notification-table-footer p { margin: 0; }
            .notification-table-footer nav { margin-left: auto; }
            .dark .notification-table { color: rgb(243 244 246); }
            .dark .notification-table th { border-bottom-color: rgb(107 114 128); }
            .dark .notification-table td { border-bottom-color: rgb(75 85 99); }
            .dark .notification-title-cell p, .dark .notification-table-footer { color: rgb(156 163 175); }
            .dark .notification-view-action { color: rgb(243 244 246); }
            @media (max-width: 639px) {
                .notification-table-footer { align-items: flex-start; flex-direction: column; }
                .notification-table-footer nav { margin-left: 0; }
            }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
