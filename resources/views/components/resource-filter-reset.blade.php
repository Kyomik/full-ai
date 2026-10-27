@props([
    'action',
    'label' => 'Reset Filter',
])

<button
    type="button"
    {{ $attributes->class(['resource-filter-reset']) }}
    wire:click="{{ $action }}"
    wire:loading.attr="disabled"
    wire:loading.class="opacity-70"
    wire:target="{{ $action }}"
>
    <x-filament::icon icon="heroicon-o-arrow-path" wire:loading.remove wire:target="{{ $action }}" />
    <span class="resource-filter-reset-spinner" wire:loading wire:target="{{ $action }}" aria-hidden="true"></span>
    {{ $label }}
</button>

@once
    <style>
        .resource-filter-reset-spinner { width: 1rem; height: 1rem; border: 2px solid currentColor; border-right-color: transparent; border-radius: 9999px; animation: resource-filter-reset-spin .65s linear infinite; }
        @keyframes resource-filter-reset-spin { to { transform: rotate(360deg); } }
    </style>
@endonce
