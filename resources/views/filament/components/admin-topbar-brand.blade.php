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
    <style>
        @media (min-width: 1024px) {
            :root {
                --admin-topbar-height: 4rem;
                --admin-sidebar-width: 16rem;
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
            .fi-wi-stats-overview-stats-ctn:has(> :nth-child(4):last-child) {
                grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat {
                display: flex;
                min-height: 7.25rem;
                align-items: center;
                padding: .8rem 1rem !important;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat > .grid {
                display: grid;
                width: 100%;
                grid-template-columns: 2.25rem minmax(0, 1fr);
                grid-template-rows: auto auto auto;
                align-items: center;
                column-gap: .7rem;
                row-gap: .25rem !important;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat > .grid > div:first-child { display: contents; }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat-icon {
                width: 2.1rem;
                height: 2.1rem;
                grid-column: 1;
                grid-row: 1 / 4;
                color: rgb(75 85 99) !important;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat-label {
                grid-column: 2;
                grid-row: 1;
                font-size: .9rem;
                line-height: 1.25;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat-value {
                grid-column: 2;
                grid-row: 2;
                font-size: 1.8rem;
                line-height: 1.2;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat-description {
                grid-column: 2;
                grid-row: 3;
                font-size: .8rem;
                line-height: 1.2;
            }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-description { color: #64748b !important; }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-value { color: #475569 !important; }

            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-description { color: #d97706 !important; }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-value { color: #b45309 !important; }

            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-description { color: #16a34a !important; }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-value { color: #15803d !important; }

            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-description { color: #dc2626 !important; }
            .fi-dashboard-page .agency-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-value { color: #b91c1c !important; }

            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat {
                min-height: 7.75rem;
                overflow: hidden;
                padding: 0 !important;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat > .grid {
                display: grid;
                width: 100%;
                grid-template-columns: 2.25rem minmax(0, 1fr);
                grid-template-rows: auto auto;
                align-content: start;
                align-items: center;
                column-gap: .7rem;
                row-gap: .35rem !important;
                padding: 1rem .9rem 2.65rem;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat > .grid > div:first-child { display: contents; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat-icon {
                grid-column: 1;
                grid-row: 1 / 3;
                width: 2.15rem;
                height: 2.15rem;
                color: #667085 !important;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat-label {
                grid-column: 2;
                grid-row: 1;
                align-self: end;
                font-size: .9rem;
                line-height: 1.25;
                color: #667085 !important;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat-value {
                grid-column: 2;
                grid-row: 2;
                align-self: start;
                font-size: 1.85rem;
                line-height: 1.2;
                color: #344054 !important;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat-description {
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                display: block;
                min-height: 2rem;
                overflow: hidden;
                border-top: 1px solid rgb(229 231 235);
                padding: .55rem .9rem;
                background: transparent;
                font-size: .8rem;
                line-height: 1.2;
                white-space: nowrap;
                text-overflow: ellipsis;
                color: #667085 !important;
            }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-description { color: #64748b !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-value { color: #475569 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-description { border-top-color: rgb(100 116 139 / 28%); }

            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-description { color: #16a34a !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-value { color: #15803d !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-description { border-top-color: rgb(22 163 74 / 28%); }

            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-description { color: #0284c7 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-value { color: #0369a1 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-description { border-top-color: rgb(2 132 199 / 28%); }

            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-description { color: #d97706 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-value { color: #b45309 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-description { border-top-color: rgb(217 119 6 / 28%); }

            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(5) .fi-wi-stats-overview-stat-icon,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(5) .fi-wi-stats-overview-stat-label,
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(5) .fi-wi-stats-overview-stat-description { color: #737373 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(5) .fi-wi-stats-overview-stat-value { color: #525252 !important; }
            .fi-dashboard-page .super-admin-overview .fi-wi-stats-overview-stat:nth-child(5) .fi-wi-stats-overview-stat-description { border-top-color: rgb(115 115 115 / 28%); }
        }

        @media (min-width: 640px) and (max-width: 1023px) {
            .fi-wi-stats-overview-stats-ctn {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 639px) {
            .fi-wi-stats-overview-stats-ctn {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        .dashboard-activity-timeline {
            position: relative;
            padding-left: 1.25rem;
        }
        .dashboard-activity-timeline-line {
            position: absolute;
            top: .35rem;
            bottom: .4rem;
            left: 5px;
            width: 2px;
            border-radius: 9999px;
            background: rgb(156 163 175 / 65%);
        }
        .dashboard-activity-timeline-item {
            position: relative;
            padding-bottom: 1rem;
        }
        .dashboard-activity-timeline-item:last-child { padding-bottom: 0; }
        .dashboard-activity-timeline-dot {
            position: absolute;
            top: .15rem;
            left: -20px;
            z-index: 1;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #283d30;
            box-shadow: 0 0 0 2px #fff;
        }
        .dark .dashboard-activity-timeline-line { background: rgb(107 114 128 / 75%); }
        .dark .dashboard-activity-timeline-dot {
            background: #e5e7eb;
            box-shadow: 0 0 0 2px #111827;
        }

        @media (min-width: 1024px) {
            .fi-dashboard-page .fi-wi-widget {
                display: flex;
                height: 100%;
                flex-direction: column;
                align-self: stretch;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-wi-stats-overview {
                display: flex;
                height: 100%;
                flex: 1 1 auto;
                flex-direction: column;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-section {
                display: flex;
                height: 100%;
                flex: 1 1 auto;
                flex-direction: column;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-section > .fi-section-content-ctn {
                display: flex;
                min-height: 0;
                flex: 1 1 auto;
                flex-direction: column;
            }

            .fi-dashboard-page .fi-wi-widget > .fi-section > .fi-section-content-ctn > .fi-section-content {
                min-height: 0;
                flex: 1 1 auto;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-chart .fi-section-content {
                display: flex;
                flex-direction: column;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-chart .fi-section-content > div {
                display: flex;
                min-height: 0;
                flex: 1 1 auto;
                flex-direction: column;
                justify-content: center;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-chart .fi-section-content > div > div {
                display: flex;
                min-height: 0;
                flex: 1 1 auto;
                align-items: center;
                justify-content: center;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-chart .fi-section-content canvas {
                width: 100% !important;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-table .fi-ta-header-cell {
                padding-top: .5rem !important;
                padding-bottom: .5rem !important;
                font-size: .7rem;
            }

            .fi-dashboard-page .fi-wi-widget.fi-wi-table .fi-ta-cell .fi-badge {
                font-size: .65rem;
                line-height: 1rem;
            }
        }

        .fi-dashboard-page .fi-wi-widget.fi-wi-table .fi-ta-cell .fi-ta-text {
            padding-top: .6rem !important;
            padding-bottom: .6rem !important;
            font-size: .75rem !important;
            line-height: 1.15;
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

        /* Keep the actions header and action buttons aligned to the left. */
        .fi-ta-actions-header-cell,
        .fi-ta-actions-cell,
        .fi-ta-actions-cell > div {
            text-align: left !important;
        }
        .fi-ta-actions-cell .fi-ta-actions {
            justify-content: flex-start !important;
        }

        /* Keep Pengguna Sistem close to the bottom Logout action. */
        .fi-sidebar-nav > .fi-sidebar-nav-groups {
            display: flex;
            min-height: 0;
            flex: 1 1 auto;
            flex-direction: column;
        }
        .fi-sidebar-nav > .fi-sidebar-nav-groups > .fi-sidebar-group,
        .fi-sidebar-nav > .fi-sidebar-nav-groups > .fi-sidebar-group > .fi-sidebar-group-items {
            min-height: 0;
            flex: 1 1 auto;
        }
        .fi-sidebar-nav .fi-sidebar-item:has(> a[href*="/users"]) {
            margin-top: auto;
            padding-top: .5rem;
            border-top: 1px solid rgb(209 213 219);
        }
        .dark .fi-sidebar-nav .fi-sidebar-item:has(> a[href*="/users"]) {
            border-color: rgb(75 85 99);
        }

        @media (max-width: 1023px) {
            .fi-sidebar-nav {
                display: flex;
                height: 100%;
                min-height: 100%;
                flex-direction: column;
            }

            .fi-sidebar-nav > .fi-sidebar-nav-groups,
            .fi-sidebar-nav > .fi-sidebar-nav-groups > .fi-sidebar-group,
            .fi-sidebar-nav > .fi-sidebar-nav-groups > .fi-sidebar-group > .fi-sidebar-group-items {
                flex: 1 1 auto;
            }

            .fi-sidebar-nav .fi-sidebar-item:has(> a[href*="/users"]) {
                margin-top: auto !important;
            }
        }

        .fi-ta-actions .fi-ac-action.is-action-loading,
        a.fi-btn.is-action-loading,
        button.fi-btn.is-action-loading,
        .reports-action.is-action-loading {
            pointer-events: none;
            opacity: .65;
        }
        .fi-ta-actions .fi-ac-action.is-action-loading svg,
        a.fi-btn.is-action-loading svg,
        button.fi-btn.is-action-loading svg,
        .reports-action.is-action-loading svg {
            display: none;
        }
        .fi-ta-actions .fi-ac-action.is-action-loading::before,
        a.fi-btn.is-action-loading::before,
        button.fi-btn.is-action-loading::before,
        .reports-action.is-action-loading::before {
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 9999px;
            content: '';
            animation: table-action-spin .65s linear infinite;
        }
        @keyframes table-action-spin {
            to { transform: rotate(360deg); }
        }

        .fi-topbar nav > div.ms-auto {
            flex: 0 0 auto;
            justify-content: flex-end;
        }

        body::before {
            position: fixed;
            z-index: 100;
            top: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #15803d;
            content: '';
            opacity: 0;
            transition: width .18s ease, opacity .12s ease;
        }

        body.is-filament-navigating::before {
            width: 72%;
            opacity: 1;
        }

        body.is-filament-navigating.is-filament-navigation-complete::before {
            width: 100%;
        }

        .admin-topbar-brand img {
            width: 2.5rem;
            height: 2.5rem;
            flex: none;
            object-fit: contain;
        }

        .admin-topbar-brand > span { min-width: 0; }
        .region-select-field { position: relative; }
        .region-select-field.is-loading::after {
            position: absolute;
            right: .75rem;
            bottom: .7rem;
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 9999px;
            content: '';
            pointer-events: none;
            animation: region-select-spin .65s linear infinite;
        }
        @keyframes region-select-spin { to { transform: rotate(360deg); } }
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
