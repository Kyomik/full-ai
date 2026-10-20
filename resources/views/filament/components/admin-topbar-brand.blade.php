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

    <a class="admin-topbar-brand" href="{{ filament()->getUrl() }}" aria-label="Sistem Informasi Festival dan Event Budaya Kota Palopo">
        <img src="{{ asset('images/palopo-seal.jpeg') }}" alt="Lambang Kota Palopo">
        <span>
            <span class="admin-topbar-brand-kicker">SISTEM INFORMASI</span>
            <span class="admin-topbar-brand-title">FESTIVAL &amp; EVENT BUDAYA KOTA PALOPO</span>
        </span>
    </a>
</div>

@once
    <style>
        @media (min-width: 1024px) {
            :root {
                --admin-topbar-height: 4rem;
                --admin-sidebar-width: 12.25rem;
                --admin-sidebar-collapsed-width: 4.5rem;
            }

            .fi-layout {
                display: block !important;
                min-height: 100vh;
            }

            .fi-main-ctn {
                display: block !important;
                width: auto !important;
                min-width: 0;
                padding-top: var(--admin-topbar-height);
            }

            .fi-topbar {
                position: fixed !important;
                z-index: 40;
                inset: 0 0 auto;
                width: 100% !important;
                height: var(--admin-topbar-height);
            }

            .fi-topbar nav { width: 100%; }

            .fi-main-ctn > .fi-main,
            .fi-main-ctn > :not(.fi-topbar):not(.fi-main) {
                width: auto !important;
                min-width: 0;
                margin-left: var(--admin-sidebar-collapsed-width);
                transition: margin-left .2s ease;
            }

            .fi-sidebar {
                position: fixed !important;
                z-index: 30;
                inset: var(--admin-topbar-height) auto 0 0;
                width: var(--admin-sidebar-collapsed-width) !important;
                min-width: var(--admin-sidebar-collapsed-width);
                height: auto !important;
                min-height: 0;
                max-height: calc(100vh - var(--admin-topbar-height));
                overflow: hidden !important;
                transform: none !important;
                transition: width .2s ease;
            }

            .fi-layout:has(.fi-main-ctn-sidebar-open) .fi-sidebar {
                width: var(--admin-sidebar-width) !important;
                min-width: var(--admin-sidebar-width);
            }

            .fi-layout:has(.fi-main-ctn-sidebar-open) .fi-main-ctn > .fi-main,
            .fi-layout:has(.fi-main-ctn-sidebar-open) .fi-main-ctn > :not(.fi-topbar):not(.fi-main) {
                margin-left: var(--admin-sidebar-width);
            }

            .fi-sidebar-nav {
                height: 100% !important;
                min-height: 0 !important;
                overflow: hidden !important;
                overflow-y: hidden !important;
                scrollbar-gutter: auto !important;
                box-sizing: border-box;
                padding-right: 1.5rem !important;
                padding-left: 1.5rem !important;
            }

            .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav {
                padding-right: 1.5rem !important;
                padding-left: 1.5rem !important;
            }

            .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-button {
                width: 100%;
                justify-content: center;
                padding-right: 0 !important;
                padding-left: 0 !important;
            }

            .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-icon {
                width: 1.5rem;
                height: 1.5rem;
                flex: none;
            }
        }

        .fi-layout { width: 100%; }
        .fi-topbar nav { padding-right: 1rem !important; padding-left: 1rem !important; }

        /* Filament 3.3 does not generate a five-column class for stats widgets. */
        @media (min-width: 1024px) {
            .fi-wi-stats-overview-stats-ctn {
                grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            }
        }

        @media (min-width: 640px) and (max-width: 1023px) {
            .fi-wi-stats-overview-stats-ctn {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (min-width: 1024px) {
            .fi-dashboard-page .fi-wi-widget {
                display: flex;
                height: 100%;
                flex-direction: column;
                align-self: stretch;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-wi-chart,
            .fi-dashboard-page .fi-wi-widget > .fi-wi-table,
            .fi-dashboard-page .fi-wi-widget > .fi-wi-stats-overview {
                display: flex;
                height: 100%;
                flex: 1 1 auto;
                flex-direction: column;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-wi-chart > .fi-section,
            .fi-dashboard-page .fi-wi-widget > .fi-wi-table > .fi-section {
                height: 100%;
                flex: 1 1 auto;
            }
        }

        .fi-topbar .fi-topbar-open-sidebar-btn,
        .fi-topbar .fi-topbar-close-sidebar-btn { display: none !important; }

        .fi-topbar .admin-topbar-left {
            display: flex;
            min-width: 0;
            flex: 1 1 auto;
            align-items: center;
            gap: 1rem;
        }

        .fi-topbar .admin-topbar-sidebar-toggle { flex: none; }

        .fi-topbar .admin-topbar-brand {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: .65rem;
            color: inherit;
            text-decoration: none;
        }

        .fi-sidebar-header { display: none !important; }
        .fi-sidebar-nav {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
            padding-top: .5rem !important;
            padding-bottom: .5rem !important;
            row-gap: .25rem !important;
        }
        .fi-sidebar-nav-groups { row-gap: .25rem !important; }
        .fi-sidebar-item-button,
        .fi-sidebar-group-button {
            min-height: 2rem !important;
            padding-top: .25rem !important;
            padding-bottom: .25rem !important;
        }
        .fi-sidebar-item.fi-active > .fi-sidebar-item-button {
            border: 1px solid rgb(209 213 219);
        }

        /* Keep the account-management items visually separated from navigation. */
        .fi-sidebar-nav-groups .fi-sidebar-item:has(> a[href*="/users"]) {
            margin-top: .5rem;
            padding-top: .5rem;
            border-top: 1px solid rgb(209 213 219);
        }
        .dark .fi-sidebar-nav-groups .fi-sidebar-item:has(> a[href*="/users"]) {
            border-color: rgb(75 85 99);
        }

        .fi-topbar nav > div.ms-auto {
            flex: 0 0 auto;
            justify-content: flex-end;
        }

        .admin-topbar-brand img {
            width: 2.5rem;
            height: 2.5rem;
            flex: none;
            object-fit: contain;
        }

        .admin-topbar-brand > span { min-width: 0; }
        .admin-topbar-brand-kicker,
        .admin-topbar-brand-title { display: block; line-height: 1.25; }
        .admin-topbar-brand-kicker {
            color: rgb(107 114 128);
            font-size: .625rem;
            font-weight: 600;
            letter-spacing: .08em;
        }
        .admin-topbar-brand-title {
            color: rgb(17 24 39);
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .015em;
        }
        .dark .admin-topbar-brand-kicker { color: rgb(156 163 175); }
        .dark .admin-topbar-brand-title { color: rgb(255 255 255); }

        @media (max-width: 639px) {
            .fi-topbar .admin-topbar-left { gap: .5rem; }
            .admin-topbar-brand { gap: .4rem !important; }
            .admin-topbar-brand img { width: 2rem; height: 2rem; }
            .admin-topbar-brand-kicker { font-size: .5rem; }
            .admin-topbar-brand-title { max-width: 11rem; font-size: .625rem; }
        }
    </style>
@endonce
