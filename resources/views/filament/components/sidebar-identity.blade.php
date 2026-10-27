@php
    $sidebarUser = filament()->auth()->user();
    $sidebarRole = match ($sidebarUser?->role) {
        'super_admin' => 'SUPER ADMIN',
        'agency_admin' => 'ADMIN PENYELENGGARA',
        default => 'PENGGUNA',
    };
    $sidebarLogo = $sidebarUser?->role === 'agency_admin' ? $sidebarUser->agency?->logo : null;
@endphp

<div @class(['sidebar-identity', 'sidebar-identity-super-admin' => $sidebarUser?->role === 'super_admin'])>
    <span class="sidebar-identity-icon">
        @if ($sidebarLogo)
            <img src="{{ '/storage/' . ltrim($sidebarLogo, '/') }}" alt="Logo {{ $sidebarUser->agency?->name }}">
        @else
            <x-filament::icon icon="heroicon-o-user" />
        @endif
    </span>
    <span class="sidebar-identity-copy" x-show="$store.sidebar.isOpen" x-transition:enter="lg:transition lg:delay-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <span class="sidebar-identity-role">{{ $sidebarRole }}</span>
        @if ($sidebarUser?->role === 'agency_admin' && $sidebarUser?->agency)
            <span class="sidebar-identity-agency">{{ $sidebarUser->agency->name }}</span>
        @endif
    </span>
</div>

@once
    <style>
        @media (min-width: 1024px) {
            .fi-sidebar {
                border-right: 1px solid rgb(209 213 219);
                background: #fff !important;
            }
            .dark .fi-sidebar { border-color: rgb(55 65 81); background: rgb(17 24 39) !important; }
            .sidebar-identity {
                display: flex;
                min-height: 3.125rem;
                align-items: center;
                gap: .5rem;
                padding: .5rem .625rem;
                border: 1px solid rgb(209 213 219);
                border-radius: .375rem;
                color: rgb(31 41 55);
                margin-bottom: 1rem;
                margin-right: -.5rem;
                margin-left: -.5rem;
            }
            .dark .sidebar-identity { border-color: rgb(75 85 99); color: rgb(243 244 246); }
            .sidebar-identity-icon {
                display: grid;
                width: 1.75rem;
                height: 1.75rem;
                flex: none;
                place-items: center;
                border: 1px solid rgb(156 163 175);
                border-radius: .375rem;
            }
            .sidebar-identity-icon svg { width: 1.25rem; height: 1.25rem; }
            .sidebar-identity-icon img { width: 100%; height: 100%; object-fit: contain; }
            .sidebar-identity-copy { min-width: 0; }
            .sidebar-identity-role {
                display: block;
                overflow: hidden;
                font-size: .625rem;
                font-weight: 700;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .sidebar-identity-agency {
                display: block;
                overflow: hidden;
                margin-top: .125rem;
                color: rgb(107 114 128);
                font-size: .5625rem;
                line-height: 1.25;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .dark .sidebar-identity-agency { color: rgb(156 163 175); }
            .fi-sidebar:not(.fi-sidebar-open) .sidebar-identity { justify-content: center; border-color: transparent; padding-inline: 0; }
            .fi-sidebar:not(.fi-sidebar-open) .sidebar-identity-icon { width: 2rem; height: 2rem; }
        }

        @media (max-width: 1023px) {
            .sidebar-identity-super-admin .sidebar-identity-icon {
                display: none;
            }
        }
    </style>
@endonce
