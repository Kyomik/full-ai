<div class="resource-filter-panel">
    <div {{ $attributes->class(['resource-filter-grid']) }}>
        {{ $slot }}
    </div>
</div>

@once
    <style>
        .resource-filter-panel { margin-bottom: .625rem; border: 1px solid rgb(229 231 235); border-radius: .5rem; background: #fff; padding: 1rem; }
        .dark .resource-filter-panel { border-color: rgb(55 65 81); background: rgb(17 24 39); }
        .resource-filter-grid { display: grid; gap: 1rem; align-items: end; }
        .resource-filter-panel .resource-filter-field label { display: block; margin-bottom: .375rem; color: rgb(17 24 39); font-size: .75rem; font-weight: 600; }
        .dark .resource-filter-panel .resource-filter-field label { color: rgb(243 244 246); }
        .resource-filter-panel .resource-filter-field input, .resource-filter-panel .resource-filter-field select { display: block; width: 100%; min-height: 2.5rem; border: 1px solid rgb(209 213 219); border-radius: .375rem; background: #fff; padding: .625rem .75rem; color: rgb(17 24 39); font-size: .875rem; outline: none; }
        .resource-filter-panel .resource-filter-field input:focus, .resource-filter-panel .resource-filter-field select:focus { border-color: rgb(22 101 52); box-shadow: 0 0 0 1px rgb(22 101 52); }
        .dark .resource-filter-panel .resource-filter-field input, .dark .resource-filter-panel .resource-filter-field select { border-color: rgb(75 85 99); background: rgb(31 41 55); color: rgb(243 244 246); }
        .resource-filter-panel .resource-filter-date-fields { display: grid; grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); align-items: center; gap: .375rem; }
        .resource-filter-panel .resource-filter-date-fields span { color: rgb(107 114 128); font-size: .75rem; }
        .resource-filter-panel .resource-filter-reset { display: inline-flex; min-height: 2.5rem; align-items: center; justify-content: center; gap: .375rem; border: 1px solid rgb(156 163 175); border-radius: .375rem; background: rgb(249 250 251); padding: .625rem .875rem; color: rgb(31 41 55); cursor: pointer; font-size: .75rem; font-weight: 600; white-space: nowrap; box-shadow: 0 1px 2px rgb(17 24 39 / .08); transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease, transform .15s ease; }
        .resource-filter-panel .resource-filter-reset:hover { border-color: rgb(75 85 99); background: rgb(243 244 246); box-shadow: 0 2px 5px rgb(17 24 39 / .14); }
        .resource-filter-panel .resource-filter-reset:active { transform: translateY(1px); box-shadow: 0 1px 2px rgb(17 24 39 / .1); }
        .resource-filter-panel .resource-filter-reset:focus-visible { outline: 2px solid rgb(22 101 52); outline-offset: 2px; }
        .resource-filter-panel .resource-filter-reset svg { width: 1rem; height: 1rem; transition: transform .25s ease; }
        .resource-filter-panel .resource-filter-reset:hover svg { transform: rotate(-45deg); }
        .dark .resource-filter-panel .resource-filter-reset { border-color: rgb(107 114 128); background: rgb(31 41 55); color: rgb(243 244 246); }
        .dark .resource-filter-panel .resource-filter-reset:hover { border-color: rgb(156 163 175); background: rgb(55 65 81); }
    </style>
@endonce
