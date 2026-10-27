<x-filament-panels::page class="account-settings-page">
    <x-filament::section heading="Informasi Akun" description="Perbarui nama, username, email, atau password untuk akun yang sedang Anda gunakan.">
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end border-t border-gray-200 pt-4 dark:border-gray-700">
                <x-filament::button
                    type="submit"
                    icon="heroicon-o-check"
                    wire:loading.attr="disabled"
                    wire:target="save"
                >
                    Simpan Perubahan
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    

    @once
        <style>
            .settings-shortcut { display: flex; min-width: 0; align-items: center; gap: .875rem; border: 1px solid rgb(209 213 219); border-radius: .5rem; padding: 1rem; color: rgb(31 41 55); text-decoration: none; transition: border-color .15s ease, background-color .15s ease; }
            .settings-shortcut:hover { border-color: rgb(22 101 52); background: rgb(240 253 244 / .55); }
            .settings-shortcut > svg:first-child { width: 1.5rem; height: 1.5rem; flex: none; color: rgb(22 101 52); }
            .settings-shortcut span { display: grid; min-width: 0; gap: .25rem; }
            .settings-shortcut strong { font-size: .875rem; font-weight: 600; }
            .settings-shortcut small { color: rgb(75 85 99); font-size: .75rem; line-height: 1.4; }
            .settings-shortcut .settings-shortcut-arrow { width: 1rem; height: 1rem; flex: none; margin-left: auto; color: rgb(107 114 128); }
            .dark .settings-shortcut { border-color: rgb(75 85 99); color: rgb(243 244 246); }
            .dark .settings-shortcut:hover { border-color: rgb(34 197 94); background: rgb(20 83 45 / .18); }
            .dark .settings-shortcut small { color: rgb(156 163 175); }
        </style>
    @endonce
</x-filament-panels::page>
