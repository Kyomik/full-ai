<div class="admin-topbar-left">
    <x-filament::icon-button
        class="admin-topbar-sidebar-toggle"
        color="gray"
        icon="heroicon-o-bars-3"
        icon-size="lg"
        label="Buka atau lipat menu samping"
        x-data="{}"
        x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
        x-bind:aria-expanded="$store.sidebar.isOpen.toString()"
    />

    <a class="admin-topbar-brand" href="{{ filament()->getUrl() }}" wire:navigate aria-label="Sistem Informasi Festival dan Event Budaya Kota Palopo">
        <img src="{{ asset('images/palopo-seal.jpeg') }}" alt="Lambang Kota Palopo">
        <span>
            <span class="admin-topbar-brand-kicker">SISTEM INFORMASI</span>
            <span class="admin-topbar-brand-title">FESTIVAL &amp; EVENT BUDAYA KOTA PALOPO</span>
        </span>
    </a>
</div>

@once
    <script data-navigate-once>
        document.addEventListener('click', (event) => {
            const action = event.target.closest('.fi-ta-actions a.fi-ac-action, a.fi-btn[href*="/create"], .reports-action');

            if (! action || action.hasAttribute('wire:click')) {
                return;
            }

            action.classList.add('is-action-loading');
        }, true);
    </script>
@endonce

@once
    <script data-navigate-once>
        window.startRegionLoading = (element, level) => {
            const levels = ['province', 'regency', 'district', 'village'];
            const nextLevel = levels[levels.indexOf(level) + 1];
            const nextField = nextLevel
                ? document.querySelector('[data-region-level="' + nextLevel + '"]')
                : null;

            nextField?.classList.add('is-loading');
        };

        document.addEventListener('livewire:init', () => {
            Livewire.hook('commit', ({ succeed, fail }) => {
                const clearRegionLoading = () => {
                    document.querySelectorAll('.region-select-field.is-loading').forEach((field) => field.classList.remove('is-loading'));
                };

                succeed(clearRegionLoading);
                fail(clearRegionLoading);
            });

            document.addEventListener('livewire:navigating', () => {
                document.body.classList.remove('is-filament-navigation-complete');
                document.body.classList.add('is-filament-navigating');
            });

            document.addEventListener('livewire:navigated', () => {
                document.body.classList.add('is-filament-navigation-complete');

                window.setTimeout(() => {
                    document.body.classList.remove('is-filament-navigating', 'is-filament-navigation-complete');
                }, 180);
            });
        });

        window.addEventListener('reset-create-wizard', () => {
            requestAnimationFrame(() => {
                document.querySelectorAll('.fi-fo-wizard').forEach((wizard) => {
                    const state = window.Alpine?.$data(wizard);

                    if (state?.getSteps) {
                        state.step = state.getSteps()[0];
                    }
                });
            });
        });
    </script>
@endonce
