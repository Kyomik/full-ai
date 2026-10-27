<div
    x-data="{ showBadge: true }"
    x-on:click="showBadge = false"
    x-bind:class="{ 'badge-dismissed': ! showBadge }"
    class="notification-bell-trigger inline-flex h-10 items-center self-center"
>
    <x-filament::icon-button
        :badge="$unreadNotificationsCount ?: null"
        badge-color="danger"
        color="gray"
        icon="heroicon-o-bell"
        icon-alias="panels::topbar.open-database-notifications-button"
        icon-size="lg"
        :label="__('filament-panels::layout.actions.open_database_notifications.label')"
        class="fi-topbar-database-notifications-btn cursor-pointer"
    />

    <style>
        .badge-dismissed .fi-icon-btn-badge-ctn { display: none !important; }
        .notification-bell-trigger .fi-icon-btn-badge-ctn {
            inset-inline-start: calc(100% - .375rem);
            top: .375rem;
        }
        .notification-bell-trigger .fi-icon-btn-badge-ctn .fi-badge {
            background-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 0 2px #fff;
        }
        .dark .notification-bell-trigger .fi-icon-btn-badge-ctn .fi-badge {
            box-shadow: 0 0 0 2px rgb(17 24 39);
        }
    </style>
</div>
