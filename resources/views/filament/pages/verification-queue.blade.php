<x-filament-panels::page class="verification-queue-page">
    <x-resource-filter-panel class="verification-filter-grid">
            <div class="resource-filter-field verification-filter-search">
                <label for="verification-event-search">Cari Event</label>
                <input id="verification-event-search" type="search" wire:model.live.debounce.400ms="tableSearch" placeholder="Cari judul event...">
            </div>

            <div class="resource-filter-field">
                <label for="verification-agency">Instansi</label>
                <select id="verification-agency" wire:model.live="agencyFilter">
                    <option value="">Semua Instansi</option>
                    @foreach ($this->getAgencyOptions() as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="resource-filter-field">
                <label for="verification-status">Status</label>
                <select id="verification-status" wire:model.live="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div class="resource-filter-field verification-filter-date">
                <label>Tanggal</label>
                <x-resource-filter-date-range from="submittedFrom" until="submittedUntil" />
            </div>

            <x-resource-filter-reset action="resetVerificationFilters" />
    </x-resource-filter-panel>

    {{ $this->table }}

    @once
        <style>
            .verification-queue-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .verification-filter-grid { grid-template-columns: repeat(6, minmax(0, 1fr)) auto; }
                .verification-filter-search, .verification-filter-date { grid-column: span 2; }
            }
            @media (max-width: 1023px) {
                .verification-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .verification-filter-search, .verification-filter-date { grid-column: 1 / -1; }
                .verification-queue-page .resource-filter-reset { justify-self: start; }
            }
        </style>
    @endonce
</x-filament-panels::page>
