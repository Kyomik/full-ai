<x-filament-panels::page class="agency-profile-page">
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}
        <div class="agency-profile-actions">
            <x-filament::button
                type="submit"
                icon="heroicon-o-check"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-70"
                wire:target="save"
            >
                Simpan Perubahan
            </x-filament::button>
        </div>
    </form>

    @once
        <style>
            .agency-profile-page .fi-fo-tabs { border: 1px solid rgb(209 213 219); border-radius: .5rem; background: transparent; padding: 0 1rem 1rem; }
            .agency-profile-page .fi-fo-tabs > .fi-tabs { border-bottom: 1px solid rgb(209 213 219); }
            .agency-profile-page .fi-section { border: 0; background: transparent; box-shadow: none; }
            .agency-profile-page .fi-fo-field-wrp-label { font-size: .7rem; }
            .agency-profile-page .agency-profile-actions { display: flex; justify-content: flex-end; border-top: 1px solid rgb(209 213 219); padding-top: 1rem; }
            .dark .agency-profile-page .fi-fo-tabs { border-color: rgb(75 85 99); background: transparent; }
            .dark .agency-profile-page .fi-fo-tabs > .fi-tabs, .dark .agency-profile-page .agency-profile-actions { border-color: rgb(75 85 99); }
            @media (max-width: 767px) {
                .agency-profile-page .fi-fo-tabs { padding-inline: .5rem; }
                .agency-profile-page .agency-profile-actions { justify-content: stretch; }
                .agency-profile-page .agency-profile-actions button { width: 100%; }
            }
        </style>
    @endonce
</x-filament-panels::page>
