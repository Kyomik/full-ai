@props([
    'notifications',
    'unreadNotificationsCount',
])

@php
    use Filament\Support\Enums\Alignment;

    $hasNotifications = $notifications->count();
    $isPaginated = $notifications instanceof \Illuminate\Contracts\Pagination\Paginator && $notifications->hasPages();
    $supportsNotificationDetails = method_exists($this, 'openNotification')
        && property_exists($this, 'activeNotification');
    $activeNotification = $supportsNotificationDetails ? $this->activeNotification : null;
@endphp

@once
    <style>
        /* Keep the notification drawer responsive without removing its slide feedback. */
        [wire\:key$=".modal.database-notifications.window"] {
            transition-duration: 160ms !important;
        }
    </style>
@endonce

<x-filament::modal
    :alignment="$hasNotifications ? null : Alignment::Center"
    close-button
    :description="$hasNotifications ? null : __('filament-notifications::database.modal.empty.description')"
    :heading="$hasNotifications ? null : __('filament-notifications::database.modal.empty.heading')"
    :icon="$hasNotifications ? null : 'heroicon-o-bell-slash'"
    :icon-alias="$hasNotifications ? null : 'notifications::database.modal.empty-state'"
    :icon-color="$hasNotifications ? null : 'gray'"
    id="database-notifications"
    slide-over
    :sticky-header="$hasNotifications"
    width="md"
>
    @if ($hasNotifications)
        <x-slot name="header">
            <div class="flex items-center justify-between gap-x-3 w-full">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                        Notifikasi
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Klik pada notifikasi untuk melihat detail lengkap.
                    </p>
                </div>

                @if ($unreadNotificationsCount > 0)
                    <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-500/10 dark:text-primary-400 dark:ring-primary-500/20">
                        {{ $unreadNotificationsCount }} baru
                    </span>
                @endif
            </div>
        </x-slot>

        <div
            @class([
                '-mx-6 -mt-6 divide-y divide-gray-200 dark:divide-white/10',
                '-mb-6' => ! $isPaginated,
                'border-b border-gray-200 dark:border-white/10' => $isPaginated,
            ])
        >
            @foreach ($notifications as $notification)
                @php
                    $data = $notification->data ?? [];
                    $isUnread = ! $supportsNotificationDetails
                        || ! $this->isNotificationReadForSession($notification->id, $notification->read_at !== null);
                @endphp

                <div
                    wire:key="sidebar-notif-{{ $notification->id }}"
                    @if ($supportsNotificationDetails)
                        wire:click="openNotification('{{ $notification->id }}')"
                    @endif
                    @class([
                        'relative cursor-pointer px-6 py-4 transition duration-150 hover:bg-gray-50 dark:hover:bg-white/5',
                        'bg-primary-50/20 dark:bg-primary-950/10' => $isUnread,
                        'before:absolute before:inset-y-0 before:start-0 before:w-1 before:bg-primary-600 dark:before:bg-primary-500' => $isUnread,
                    ])
                >
                    <div class="flex items-start gap-x-3">
                        <div @class([
                            'mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg',
                            'bg-primary-100 text-primary-600 dark:bg-primary-500/20 dark:text-primary-400' => $isUnread,
                            'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500' => ! $isUnread,
                        ])>
                            <x-filament::icon icon="heroicon-o-bell" class="h-4 w-4" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-x-2">
                                <h3 @class([
                                    'truncate text-sm leading-snug',
                                    'font-semibold text-gray-950 dark:text-white' => $isUnread,
                                    'font-normal text-gray-700 dark:text-gray-300' => ! $isUnread,
                                ])>
                                    {{ $data['title'] ?? 'Pemberitahuan' }}
                                </h3>

                                <time class="shrink-0 text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </time>
                            </div>

                            <p class="mt-1 line-clamp-2 text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $data['message'] ?? ($data['body'] ?? 'Tidak ada rincian pesan.') }}
                            </p>

                            <div class="mt-2 flex items-center gap-x-2">
                                <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    {{ ! empty($data['event_id']) ? 'Pengajuan' : ($data['category'] ?? 'Sistem') }}
                                </span>

                                @if ($isUnread)
                                    <span class="inline-flex items-center gap-x-1 text-[10px] font-medium text-primary-600 dark:text-primary-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                                        Belum dibaca
                                    </span>
                                @else
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                        Sudah dibaca
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($isPaginated)
            <x-slot name="footer">
                <x-filament::pagination :paginator="$notifications" />
            </x-slot>
        @endif
    @endif
</x-filament::modal>

{{-- Modal detail notifikasi saat diklik dari slideover --}}
<x-filament::modal
    id="sidebar-notification-detail-modal"
    width="md"
>
    @if ($activeNotification)
        <x-slot name="heading">
            {{ $activeNotification['title'] }}
        </x-slot>

        <x-slot name="description">
            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center rounded bg-blue-100 px-2 py-0.5 font-semibold text-blue-900 dark:bg-blue-950 dark:text-blue-200">
                    {{ $activeNotification['category'] }}
                </span>
                <span>•</span>
                <span>{{ $activeNotification['date'] }}</span>
            </div>
        </x-slot>

        <div class="py-2 text-sm leading-relaxed text-gray-900 dark:text-gray-100">
            {{ $activeNotification['message'] }}
        </div>

        <x-slot name="footer">
            <div class="flex w-full items-center justify-end gap-x-3">
                <x-filament::button
                    color="gray"
                    x-on:click="$dispatch('close-modal', { id: 'sidebar-notification-detail-modal' })"
                >
                    Tutup
                </x-filament::button>

                @if (! empty($activeNotification['event_id']))
                    @php
                        $userRole = auth()->user()?->role;
                        $panelPrefix = $userRole === 'super_admin' ? 'super' : 'admin';
                    @endphp
                    <x-filament::button
                        tag="a"
                        :href="url('/' . $panelPrefix . '/cultural-events/' . $activeNotification['event_id'] . '/edit')"
                        color="primary"
                        icon="heroicon-m-arrow-top-right-on-square"
                    >
                        Lihat Data Event
                    </x-filament::button>
                @endif
            </div>
        </x-slot>
    @endif
</x-filament::modal>
