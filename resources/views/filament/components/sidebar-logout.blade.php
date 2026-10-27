@auth
<div class="sidebar-logout-anchor">
<ul class="fi-sidebar-nav-groups -mx-2 flex flex-col gap-y-7">
    <li class="fi-sidebar-group flex flex-col gap-y-1">
        <ul class="fi-sidebar-group-items flex flex-col gap-y-1">
            <li class="fi-sidebar-item">
                <button
                    type="button"
                    x-on:click="$dispatch('open-modal', { id: 'sidebar-logout-modal' })"
                    x-data="{ tooltip: false }"
                    x-effect="
                        tooltip = $store.sidebar.isOpen
                            ? false
                            : {
                                  content: 'Logout',
                                  placement: document.dir === 'rtl' ? 'left' : 'right',
                                  theme: $store.theme,
                              }
                    "
                    x-tooltip.html="tooltip"
                    class="fi-sidebar-item-button relative flex w-full items-center justify-start gap-x-3 rounded-lg px-2 py-2 text-start outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
                >
                    <x-filament::icon
                        icon="heroicon-o-arrow-left-on-rectangle"
                        class="fi-sidebar-item-icon h-6 w-6 text-gray-400 dark:text-gray-500"
                    />

                    <span
                        x-show="$store.sidebar.isOpen"
                        x-transition:enter="lg:transition lg:delay-100"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="fi-sidebar-item-label flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                        Logout
                    </span>
                </button>
            </li>
        </ul>
    </li>
</ul>
</div>

@once
    <style>
        .fi-sidebar-nav > .sidebar-logout-anchor {
            margin-top: 0;
            padding-top: 0;
        }
        @media (max-width: 1023px) {
            .fi-sidebar-nav > .sidebar-logout-anchor {
                margin-top: .25rem;
                padding-top: 0;
            }
        }
    </style>
@endonce

@endauth
