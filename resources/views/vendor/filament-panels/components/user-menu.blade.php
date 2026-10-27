@php
    $user = filament()->auth()->user();
    $items = filament()->getUserMenuItems();
    $profileItem = $items['profile'] ?? $items['account'] ?? null;
    $profileItemUrl = $profileItem?->getUrl();
    $profilePage = filament()->getProfilePage();
    $hasProfileItem = filament()->hasProfile() || filled($profileItemUrl);
    $logoutItem = $items['logout'] ?? null;
    $items = \Illuminate\Support\Arr::except($items, ['account', 'logout', 'profile']);
    $roleLabel = match ($user?->role) {
        'super_admin' => 'Super Admin',
        'agency_admin' => 'Admin Instansi',
        default => 'Pengguna',
    };
    $userAgencyLogo = $user?->role === 'agency_admin' ? $user->agency?->logo : null;
@endphp

@once
    <style>
        .agency-user-avatar { width: 2rem; height: 2rem; flex: none; border: 1px solid rgb(156 163 175); border-radius: .25rem; object-fit: contain; background: #fff; }
        .dark .agency-user-avatar { border-color: rgb(75 85 99); background: rgb(31 41 55); }
        @media (min-width: 1024px) {
            .fi-topbar .fi-topbar-open-sidebar-btn,
            .fi-topbar .fi-topbar-close-sidebar-btn { display: flex; }
        }
    </style>
@endonce

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_BEFORE) }}

<x-filament::dropdown placement="bottom-end" teleport :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->class(['fi-user-menu'])">
    <x-slot name="trigger">
        <button aria-label="Menu {{ filament()->getUserName($user) }}" type="button" class="flex max-w-[min(18rem,45vw)] shrink-0 items-center gap-2 rounded-lg px-2 py-1.5 text-start transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 dark:hover:bg-white/5">
            @if ($userAgencyLogo)
                <img class="agency-user-avatar" src="{{ '/storage/' . ltrim($userAgencyLogo, '/') }}" alt="Logo {{ $user->agency?->name }}">
            @else
                <x-filament-panels::avatar.user :user="$user" />
            @endif
            <span class="hidden min-w-0 sm:block">
                <span class="block truncate text-sm font-semibold text-gray-950 dark:text-white">{{ filament()->getUserName($user) }}</span>
                <span class="block truncate text-xs text-gray-500 dark:text-gray-400">{{ $roleLabel }}@if ($user?->role === 'agency_admin' && $user?->agency) · {{ $user->agency->name }}@endif</span>
            </span>
            <x-filament::icon icon="heroicon-m-chevron-down" class="hidden h-4 w-4 text-gray-400 sm:block" />
        </button>
    </x-slot>

    @if ($profileItem?->isVisible() ?? true)
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_BEFORE) }}
        @if ($hasProfileItem)
            <x-filament::dropdown.list>
                <x-filament::dropdown.list.item :color="$profileItem?->getColor()" :icon="$profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.profile-item') ?? 'heroicon-m-user-circle'" :href="$profileItemUrl ?? filament()->getProfileUrl()" :target="($profileItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null" tag="a">
                    {{ $profileItem?->getLabel() ?? ($profilePage ? $profilePage::getLabel() : null) ?? filament()->getUserName($user) }}
                </x-filament::dropdown.list.item>
            </x-filament::dropdown.list>
        @else
            <x-filament::dropdown.header :color="$profileItem?->getColor()" :icon="$profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.profile-item') ?? 'heroicon-m-user-circle'">
                {{ $profileItem?->getLabel() ?? filament()->getUserName($user) }}
            </x-filament::dropdown.header>
        @endif
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_PROFILE_AFTER) }}
    @endif

    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
        <x-filament::dropdown.list><x-filament-panels::theme-switcher /></x-filament::dropdown.list>
    @endif

    <x-filament::dropdown.list>
        @foreach ($items as $item)
            @php $itemPostAction = $item->getPostAction(); @endphp
            <x-filament::dropdown.list.item :action="$itemPostAction" :color="$item->getColor()" :href="$item->getUrl()" :icon="$item->getIcon()" :method="filled($itemPostAction) ? 'post' : null" :tag="filled($itemPostAction) ? 'form' : 'a'" :target="$item->shouldOpenUrlInNewTab() ? '_blank' : null">
                {{ $item->getLabel() }}
            </x-filament::dropdown.list.item>
        @endforeach
        <x-filament::dropdown.list.item
            :color="$logoutItem?->getColor()"
            :icon="$logoutItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.logout-button') ?? 'heroicon-m-arrow-left-on-rectangle'"
            tag="button"
            type="button"
            x-on:click="$dispatch('open-modal', { id: 'sidebar-logout-modal' })"
        >
            {{ $logoutItem?->getLabel() ?? __('filament-panels::layout.actions.logout.label') }}
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
