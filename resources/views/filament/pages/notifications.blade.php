<x-filament-panels::page class="notification-page resource-flat-table">
    @if (auth()->user()?->role === 'agency_admin')
        <section class="agency-notification-page" aria-label="Daftar notifikasi">
            <div class="agency-notification-toolbar">
                <div class="agency-notification-tabs" role="tablist" aria-label="Filter notifikasi">
                    @foreach ([
                        'all' => 'Semua',
                        'unread' => 'Belum Dibaca',
                        'submission' => 'Pengajuan',
                        'information' => 'Informasi',
                        'announcement' => 'Pengumuman',
                        'system' => 'Sistem',
                    ] as $filter => $label)
                        <button
                            type="button"
                            role="tab"
                            @class(['agency-notification-tab', 'is-active' => $agencyFilter === $filter])
                            wire:click="setAgencyFilter('{{ $filter }}')"
                            aria-selected="{{ $agencyFilter === $filter ? 'true' : 'false' }}"
                        >
                            {{ $label }} ({{ $agencyNotificationCounts[$filter] ?? 0 }})
                        </button>
                    @endforeach
                </div>

                <x-filament::button
                    type="button"
                    color="primary"
                    icon="heroicon-o-envelope-open"
                    class="agency-mark-all"
                    wire:click="markAllAgencyNotificationsAsRead"
                    wire:target="markAllAgencyNotificationsAsRead"
                >
                    Tandai semua sebagai dibaca
                </x-filament::button>
            </div>

            <div class="agency-notification-list">
                @forelse ($agencyNotifications as $notification)
                    @php($data = $notification->data ?? [])
                    @php($isUnread = is_null($notification->read_at) && ! in_array((string) $notification->id, $pendingNotificationIds, true))
                    <article
                        @class(['agency-notification-card', 'is-unread' => $isUnread])
                        wire:key="agency-notification-{{ $notification->id }}"
                        wire:click="viewNotification('{{ $notification->id }}')"
                        wire:keydown.enter="viewNotification('{{ $notification->id }}')"
                        wire:keydown.space.prevent="viewNotification('{{ $notification->id }}')"
                        role="button"
                        tabindex="0"
                        aria-label="Buka notifikasi: {{ $data['title'] ?? 'Pemberitahuan' }}"
                    >
                        <div class="agency-notification-icon">
                            <x-filament::icon icon="heroicon-o-bell" />
                        </div>
                        <div class="agency-notification-copy">
                            <h2>{{ $data['title'] ?? 'Pemberitahuan' }}</h2>
                            <p>{{ $data['message'] ?? 'Tidak ada rincian notifikasi.' }}</p>
                            <span @class([
                                'agency-notification-type',
                                'is-submission' => ! empty($data['event_id']),
                                'is-information' => empty($data['event_id']) && ($data['category'] ?? '') === 'information',
                                'is-announcement' => empty($data['event_id']) && ($data['category'] ?? '') === 'announcement',
                                'is-system' => empty($data['event_id']) && ! in_array($data['category'] ?? 'system', ['information', 'announcement'], true),
                            ])>
                                {{ $this->notificationTypeLabel($data) }}
                            </span>
                        </div>
                        <div class="agency-notification-meta">
                            <time datetime="{{ $notification->created_at?->toIso8601String() }}">
                                {{ $notification->created_at?->translatedFormat('d M Y, H:i') }}
                            </time>
                            <span class="agency-notification-dot" aria-label="{{ $isUnread ? 'Belum dibaca' : 'Sudah dibaca' }}"></span>
                        </div>
                    </article>
                @empty
                    <div class="agency-notification-empty">Tidak ada notifikasi pada kategori ini.</div>
                @endforelse
            </div>

            <footer class="agency-notification-footer">
                <p>
                    @if ($agencyNotifications->total() > 0)
                        Menampilkan {{ $agencyNotifications->firstItem() }} - {{ $agencyNotifications->lastItem() }} dari {{ $agencyNotifications->total() }} notifikasi
                    @else
                        Menampilkan 0 notifikasi
                    @endif
                </p>
                {{ $agencyNotifications->links() }}
            </footer>

            @once
                <style>
                    .agency-notification-page { min-width: 0; }
                    .agency-notification-toolbar { display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem; margin-bottom: .25rem; border-bottom: 1px solid rgb(209 213 219); }
                    .agency-notification-tabs { display: flex; flex-wrap: wrap; gap: 1.75rem; overflow: hidden; }
                    .agency-notification-tab { position: relative; border: 0; background: transparent; padding: .75rem .15rem .8rem; color: rgb(75 85 99); cursor: pointer; font-size: .75rem; white-space: nowrap; }
                    .agency-notification-tab::after { position: absolute; right: 0; bottom: -1px; left: 0; height: 2px; background: transparent; content: ''; }
                    .agency-notification-tab.is-active { color: rgb(17 24 39); font-weight: 700; }
                    .agency-notification-tab.is-active::after { background: rgb(17 24 39); }
                    .agency-mark-all { flex: none; margin-bottom: .5rem; font-size: .75rem; font-weight: 700; }
                    .agency-mark-all svg { width: 1rem; height: 1rem; }
                    .agency-notification-list { overflow: hidden; border: 1px solid rgb(209 213 219); border-radius: .55rem; background: #fff; }
                    .agency-notification-card { display: grid; grid-template-columns: 4.25rem minmax(0, 1fr) auto; align-items: center; gap: 1rem; min-height: 6.9rem; padding-inline: 1.25rem; border-bottom: 1px solid rgb(209 213 219); cursor: pointer; transition: background-color .15s ease; }
                    .agency-notification-card:hover { background: rgb(249 250 251); }
                    .agency-notification-card:focus-visible { outline: 2px solid rgb(37 99 235); outline-offset: -2px; }
                    .agency-notification-card:last-child { border-bottom: 0; }
                    .agency-notification-icon { display: grid; width: 3.5rem; height: 3.5rem; place-items: center; border: 1px solid rgb(156 163 175); color: rgb(31 41 55); }
                    .agency-notification-icon svg { width: 1.45rem; height: 1.45rem; }
                    .agency-notification-copy { min-width: 0; padding: 1rem 0; }
                    .agency-notification-copy h2 { margin: 0; color: rgb(17 24 39); font-size: .875rem; font-weight: 700; }
                    .agency-notification-copy p { margin: .25rem 0 .4rem; color: rgb(31 41 55); font-size: .75rem; line-height: 1.5; }
                    .agency-notification-type { display: inline-flex; border: 1px solid transparent; border-radius: .25rem; padding: .18rem .45rem; font-size: .65rem; font-weight: 600; }
                    .agency-notification-type.is-submission { border-color: rgb(196 181 253); background: rgb(245 243 255); color: rgb(91 33 182); }
                    .agency-notification-type.is-information { border-color: rgb(147 197 253); background: rgb(239 246 255); color: rgb(29 78 216); }
                    .agency-notification-type.is-announcement { border-color: rgb(253 230 138); background: rgb(255 251 235); color: rgb(180 83 9); }
                    .agency-notification-type.is-system { border-color: rgb(209 213 219); background: rgb(249 250 251); color: rgb(55 65 81); }
                    .agency-notification-meta { display: flex; min-width: 9.5rem; align-self: stretch; align-items: center; justify-content: flex-end; gap: .75rem; padding: 1rem 0; color: rgb(31 41 55); font-size: .7rem; white-space: nowrap; }
                    .agency-notification-dot { width: .8rem; height: .8rem; flex: none; border: 1.5px solid rgb(156 163 175); border-radius: 999px; background: transparent; }
                    .agency-notification-card.is-unread .agency-notification-dot { border-color: rgb(220 38 38); background: rgb(220 38 38); }
                    .agency-notification-empty { padding: 3rem 1rem; color: rgb(107 114 128); text-align: center; font-size: .8rem; }
                    .agency-notification-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .8rem 1.25rem 0; color: rgb(75 85 99); font-size: .7rem; }
                    .agency-notification-footer p { margin: 0; }
                    .agency-notification-footer nav { margin-left: auto; }
                    .dark .agency-notification-toolbar, .dark .agency-notification-list, .dark .agency-notification-card { border-color: rgb(75 85 99); }
                    .dark .agency-notification-list { background: rgb(17 24 39); }
                    .dark .agency-notification-card:hover { background: rgb(31 41 55); }
                    .dark .agency-notification-tab, .dark .agency-notification-meta, .dark .agency-notification-copy p, .dark .agency-notification-footer { color: rgb(156 163 175); }
                    .dark .agency-notification-tab.is-active, .dark .agency-notification-copy h2 { color: rgb(243 244 246); }
                    .dark .agency-notification-type.is-submission { border-color: rgb(109 40 217); background: rgb(46 16 101); color: rgb(221 214 254); }
                    .dark .agency-notification-type.is-information { border-color: rgb(30 64 175); background: rgb(30 58 138); color: rgb(191 219 254); }
                    .dark .agency-notification-type.is-announcement { border-color: rgb(146 64 14); background: rgb(120 53 15); color: rgb(253 230 138); }
                    .dark .agency-notification-type.is-system { border-color: rgb(75 85 99); background: rgb(31 41 55); color: rgb(229 231 235); }
                    @media (max-width: 767px) {
                        .agency-notification-toolbar { align-items: stretch; flex-direction: column-reverse; border-bottom: 0; }
                        .agency-mark-all { align-self: flex-start; }
                        .agency-notification-tabs { border-bottom: 1px solid rgb(209 213 219); gap: 1rem; }
                        .agency-notification-card { grid-template-columns: 3rem minmax(0, 1fr); gap: .75rem; padding-inline: .75rem; }
                        .agency-notification-icon { width: 2.75rem; height: 2.75rem; }
                        .agency-notification-meta { grid-column: 2; min-width: 0; align-self: auto; align-items: flex-start; flex-direction: row; padding: 0 0 .75rem; }
                        .agency-notification-footer { align-items: flex-start; flex-direction: column; padding-inline: .75rem; }
                        .agency-notification-footer nav { margin-left: 0; }
                    }
                </style>
            @endonce
        </section>
    @else
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
                <option value="scheduled">Terjadwal</option>
                <option value="failed">Gagal</option>
            </select>
        </div>

        <x-resource-filter-reset action="resetFilters" />
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
                        @php($deliveryStatus = $data['delivery_status'] ?? 'sent')
                        @php($deliveryStatusLabel = match ($deliveryStatus) {
                            'scheduled' => 'Terjadwal',
                            'processing' => 'Diproses',
                            'failed' => 'Gagal',
                            default => 'Terkirim',
                        })
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
                            <td class="notification-date">
                                @if (! empty($data['scheduled_at']) && $deliveryStatus === 'scheduled')
                                    {{ \Illuminate\Support\Carbon::parse($data['scheduled_at'])->translatedFormat('d M Y, H:i') }} WIB
                                @else
                                    {{ $notification->created_at?->translatedFormat('d M Y, H:i') ?? '—' }} WIB
                                @endif
                            </td>
                            <td>
                                <span @class(['notification-status', 'is-sent' => $deliveryStatus === 'sent', 'is-scheduled' => $deliveryStatus === 'scheduled', 'is-failed' => $deliveryStatus === 'failed'])>
                                    {{ $deliveryStatusLabel }}
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
            .notification-title-cell { display: flex; min-width: 13rem; align-items: center; gap: .75rem; }
            .notification-title-cell > svg { width: 1.25rem; height: 1.25rem; flex: none; }
            .notification-title-cell strong { display: block; font-weight: 700; }
            .notification-title-cell p { max-width: 17rem; margin: .25rem 0 0; color: rgb(75 85 99); line-height: 1.5; }
            .notification-message { max-width: 16rem; line-height: 1.55; }
            .notification-date { min-width: 7rem; white-space: nowrap; }
            .notification-status { display: inline-flex; border: 1px solid rgb(156 163 175); border-radius: .25rem; padding: .2rem .45rem; font-size: .625rem; white-space: nowrap; }
            .notification-status.is-sent { border-color: rgb(21 128 61); color: rgb(21 128 61); }
            .notification-status.is-scheduled { border-color: rgb(180 83 9); color: rgb(180 83 9); }
            .notification-status.is-failed { border-color: rgb(185 28 28); color: rgb(185 28 28); }
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
    @endif

    <x-filament::modal id="page-notification-detail-modal" width="md">
        @if ($activeNotification)
            <x-slot name="heading">
                {{ $activeNotification['title'] }}
            </x-slot>

            <x-slot name="description">
                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="notification-detail-category inline-flex items-center rounded bg-blue-100 px-2 py-0.5 font-semibold text-blue-900 dark:bg-blue-950 dark:text-blue-200">
                        {{ $activeNotification['category'] }}
                    </span>
                    <span>{{ $activeNotification['date'] }}</span>
                </div>
            </x-slot>

            <div class="notification-detail-message py-2 text-sm leading-relaxed text-gray-900 dark:text-gray-100">
                {{ $activeNotification['message'] }}
            </div>

            @if (! empty($activeNotification['event_id']))
                <x-slot name="footer">
                    <x-filament::button
                        tag="a"
                        :href="\App\Filament\Resources\CulturalEventResource::getUrl('edit', ['record' => $activeNotification['event_id']])"
                        color="primary"
                        icon="heroicon-m-arrow-top-right-on-square"
                    >
                        Lihat Data Event
                    </x-filament::button>
                </x-slot>
            @endif
        @endif
    </x-filament::modal>
</x-filament-panels::page>
