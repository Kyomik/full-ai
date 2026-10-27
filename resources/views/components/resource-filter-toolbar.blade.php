<div {{ $attributes->class(['resource-filter-toolbar']) }}>
    {{ $slot }}
</div>

@once
    <style>
        .resource-filter-toolbar { display: grid; align-items: end; margin-bottom: .5rem; }
        .resource-filter-toolbar .resource-filter-field { min-width: 0; }
        .resource-filter-toolbar .resource-filter-field label { display: block; margin-bottom: .75rem; color: rgb(17 24 39); font-size: .75rem; font-weight: 600; }
        .resource-filter-toolbar .resource-search-input { display: flex; min-height: 2.5rem; align-items: center; gap: .625rem; border-bottom: 1px solid rgb(156 163 175); }
        .resource-filter-toolbar .resource-search-input svg { width: 1.125rem; height: 1.125rem; flex: none; color: rgb(75 85 99); }
        .resource-filter-toolbar .resource-filter-field input, .resource-filter-toolbar .resource-filter-field select { width: 100%; min-width: 0; min-height: 2.5rem; border: 0; border-bottom: 1px solid rgb(156 163 175); border-radius: 0; background: transparent; color: rgb(17 24 39); font-size: .875rem; outline: none; }
        .resource-filter-toolbar .resource-filter-field input { padding: .5rem 0; }
        .resource-filter-toolbar .resource-filter-field select { padding: .5rem .75rem; }
        .resource-filter-toolbar .resource-filter-field .resource-search-input input { border: 0; padding-left: 0; }
        .resource-filter-toolbar .resource-filter-field input:focus, .resource-filter-toolbar .resource-filter-field select:focus, .resource-filter-toolbar .resource-search-input:focus-within { border-color: rgb(22 101 52); box-shadow: 0 1px 0 rgb(22 101 52); }
        .resource-filter-toolbar .resource-filter-field input::placeholder { color: rgb(107 114 128); opacity: 1; }
        .resource-filter-toolbar .resource-filter-field select option { background: #fff; color: rgb(17 24 39); }
        .resource-filter-toolbar .resource-filter-reset { display: inline-flex; min-height: 2.5rem; align-items: center; justify-content: center; gap: .5rem; border: 0; background: transparent; padding: .5rem 0; color: rgb(31 41 55); cursor: pointer; font-size: .75rem; font-weight: 600; white-space: nowrap; }
        .resource-filter-toolbar .resource-filter-reset svg { width: 1.125rem; height: 1.125rem; }
        .resource-filter-toolbar .resource-filter-reset:hover { color: rgb(22 101 52); }
        .resource-filter-toolbar .resource-filter-reset:focus-visible { outline: 2px solid rgb(22 101 52); outline-offset: 3px; }
        .dark .resource-filter-toolbar .resource-filter-field label, .dark .resource-filter-toolbar .resource-filter-field input, .dark .resource-filter-toolbar .resource-filter-field select, .dark .resource-filter-toolbar .resource-filter-reset { color: rgb(243 244 246); }
        .dark .resource-filter-toolbar .resource-filter-field input, .dark .resource-filter-toolbar .resource-filter-field select, .dark .resource-filter-toolbar .resource-search-input { border-color: rgb(107 114 128); }
        .dark .resource-filter-toolbar .resource-search-input svg { color: rgb(209 213 219); }
        @media (max-width: 1023px) {
            .resource-filter-toolbar { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.25rem 1.5rem; }
            .resource-filter-toolbar .resource-filter-reset { justify-self: start; }
        }
        @media (max-width: 639px) {
            .resource-filter-toolbar { grid-template-columns: minmax(0, 1fr); }
        }
    </style>
@endonce
