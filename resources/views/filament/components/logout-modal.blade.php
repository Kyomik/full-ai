@auth
    <x-filament::modal
        id="sidebar-logout-modal"
        icon="heroicon-o-arrow-left-on-rectangle"
        icon-color="danger"
        alignment="center"
        width="sm"
    >
        <x-slot name="heading">
            Konfirmasi Logout
        </x-slot>

        <x-slot name="description">
            Apakah Anda yakin ingin keluar dari sistem?
        </x-slot>

        <x-slot name="footer">
            <div class="flex w-full items-center justify-end gap-x-3">
                <x-filament::button
                    color="gray"
                    x-on:click="$dispatch('close-modal', { id: 'sidebar-logout-modal' })"
                >
                    Batal
                </x-filament::button>

                <form id="sidebar-logout-form" method="POST" action="{{ route('panel.logout') }}" x-on:submit="$dispatch('form-processing-started')">
                    @csrf
                    <x-filament::button
                        type="submit"
                        form="sidebar-logout-form"
                        form-id="sidebar-logout-form"
                        color="danger"
                    >
                        Ya, Keluar
                    </x-filament::button>
                </form>
            </div>
        </x-slot>
    </x-filament::modal>
@endauth
