@props([
    'heading',
    'subheading' => null,
    'breadcrumbs' => [],
    'actions' => [],
])

<header wire:key="resource-page-header-{{ request()->route()?->getName() ?? 'unknown' }}" {{ $attributes->class(['resource-page-header']) }}>
    <div class="resource-page-heading">
        <h1>{{ $heading }}</h1>
        @if (filled($subheading))
            <p>{{ $subheading }}</p>
        @endif
    </div>

    <div class="resource-page-tools">
        <div wire:key="resource-page-breadcrumbs-{{ request()->route()?->getName() ?? 'unknown' }}">
            <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" />
        </div>
        <x-filament::actions :actions="$actions" />
        {{ $slot }}
    </div>
</header>

@once
    <style>
        .resource-page-header { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: 1rem 2rem; }
        .resource-page-heading { min-width: 0; }
        .resource-page-heading h1 { margin: 0; color: rgb(17 24 39); font-size: 1.875rem; font-weight: 700; line-height: 2.25rem; letter-spacing: -.025em; }
        .resource-page-heading p { margin: .5rem 0 0; color: rgb(75 85 99); font-size: 1rem; line-height: 1.5rem; }
        .resource-page-tools { display: flex; flex-direction: column; align-items: flex-end; justify-content: center; gap: .75rem; }
        .dark .resource-page-heading h1 { color: #fff; }
        .dark .resource-page-heading p { color: rgb(156 163 175); }
        @media (max-width: 767px) {
            .resource-page-header { grid-template-columns: minmax(0, 1fr); }
            .resource-page-tools { align-items: flex-start; }
        }
    </style>
@endonce
