<style>
    .fi-page .fi-header:has(> div .fi-breadcrumbs) {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: auto auto;
        align-items: start;
        gap: .5rem 1.5rem;
    }

    .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child {
        display: contents;
    }

    .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > .fi-breadcrumbs {
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
        margin: 0;
        display: flex;
        text-align: right;
    }

    .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > h1 {
        grid-column: 1;
        grid-row: 1;
        align-self: center;
    }

    .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > .fi-header-subheading {
        grid-column: 1;
        grid-row: 2;
    }

    .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:last-child {
        grid-column: 2;
        grid-row: 2;
        justify-self: end;
        align-self: start;
        margin: 0;
    }

    .fi-breadcrumbs-item:last-child .fi-breadcrumbs-item-label {
        color: rgb(22 101 52) !important;
        font-weight: 600;
    }

    .resource-page-header .fi-breadcrumbs {
        justify-content: flex-end;
        text-align: right;
    }

    .dark .fi-breadcrumbs-item:last-child .fi-breadcrumbs-item-label {
        color: rgb(134 239 172) !important;
    }

    @media (max-width: 639px) {
        .fi-page .fi-header:has(> div .fi-breadcrumbs) {
            grid-template-columns: minmax(0, 1fr);
            grid-template-rows: auto auto auto auto;
        }

        .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > .fi-breadcrumbs {
            grid-column: 1;
            grid-row: 1;
        }

        .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > h1 {
            grid-column: 1;
            grid-row: 2;
        }

        .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:first-child > .fi-header-subheading {
            grid-column: 1;
            grid-row: 3;
        }

        .fi-page .fi-header:has(> div .fi-breadcrumbs) > div:last-child {
            grid-column: 1;
            grid-row: 4;
            justify-self: start;
        }
    }
</style>
