<x-filament-panels::page>
    <x-resource-filter-toolbar class="supporting-documents-toolbar">
        <div class="resource-filter-field">
            <label for="supporting-document-search">Cari Dokumen</label>
            <div class="resource-search-input">
                <x-filament::icon icon="heroicon-o-magnifying-glass" />
                <input id="supporting-document-search" type="search" wire:model.live.debounce.400ms="search" placeholder="Nama dokumen atau event...">
            </div>
        </div>

        <div class="resource-filter-field">
            <label for="supporting-document-type">Jenis Berkas</label>
            <select id="supporting-document-type" wire:model.live="typeFilter">
                <option value="">Semua Jenis</option>
                <option value="image">Gambar</option>
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
                <option value="office">Word / Excel</option>
            </select>
        </div>

        <div class="resource-filter-field">
            <label for="supporting-document-status">Status Pengajuan</label>
            <select id="supporting-document-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>

        <x-resource-filter-reset action="resetFilters" />
    </x-resource-filter-toolbar>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Daftar Dokumen Pengajuan</h2>
            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Lampiran proposal, surat izin, foto, video, dan berkas pendukung festival/event.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-start text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-xs font-semibold text-gray-500 dark:border-gray-800 dark:bg-white/5 dark:text-gray-400">
                        <th class="px-4 py-3 text-start">No</th>
                        <th class="px-6 py-3 text-start">Nama Dokumen</th>
                        <th class="px-6 py-3 text-start">Festival / Event Terkait</th>
                        <th class="px-4 py-3 text-start">Status Pengajuan</th>
                        <th class="px-6 py-3 text-start">Tanggal Diunggah</th>
                        <th class="px-6 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->documents as $doc)
                        @php
                            $extension = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                            $videoExtensions = ['mp4', 'webm', 'mov', 'm4v'];
                            $canPreview = in_array($extension, [...$imageExtensions, ...$videoExtensions, 'pdf'], true);
                            $fileIcon = in_array($extension, $imageExtensions, true)
                                ? 'heroicon-o-photo'
                                : (in_array($extension, $videoExtensions, true) ? 'heroicon-o-video-camera' : 'heroicon-o-document-text');
                            $status = $doc->event?->status;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02]">
                            <td class="px-4 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium text-gray-950 dark:text-white">
                                <div class="flex items-center gap-2">
                                    <x-filament::icon :icon="$fileIcon" class="h-5 w-5 shrink-0 text-gray-400" />
                                    <div class="min-w-0">
                                        <span class="block truncate" title="{{ $doc->name }}">{{ $doc->name }}</span>
                                        <span class="mt-0.5 block text-xs font-normal uppercase text-gray-500 dark:text-gray-400">{{ $extension ?: 'berkas' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $doc->event?->title ?? '-' }}
                            </td>
                            <td class="px-4 py-4">
                                @if ($status)
                                    <x-filament::badge :color="match ($status) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' }">
                                        {{ match ($status) { 'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu' } }}
                                    </x-filament::badge>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $doc->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-end">
                                @if (filled($doc->file_path))
                                    <div class="flex flex-wrap items-center justify-end gap-x-3 gap-y-1.5">
                                        @if ($canPreview)
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-950 dark:text-gray-300 dark:hover:text-white">
                                                <x-filament::icon icon="heroicon-o-eye" class="h-4 w-4" />
                                                Lihat
                                            </a>
                                        @endif
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" download="{{ basename($doc->file_path) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-500 dark:text-primary-400">
                                            <x-filament::icon icon="heroicon-o-arrow-down-tray" class="h-4 w-4" />
                                            Unduh
                                        </a>
                                        @if ($doc->event)
                                            <a href="{{ \App\Filament\Resources\CulturalEventResource::getUrl('edit', ['record' => $doc->event, 'from' => 'submissions']) }}" wire:navigate class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-950 dark:text-gray-300 dark:hover:text-white">
                                                <x-filament::icon icon="heroicon-o-pencil-square" class="h-4 w-4" />
                                                Buka Pengajuan
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">- Tidak ada file -</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada berkas dokumen pendukung yang diunggah. Dokumen dapat diunggah saat mengajukan festival & event baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <footer class="supporting-documents-pagination">
        <p>
            @if ($this->documents->total() > 0)
                Menampilkan {{ $this->documents->firstItem() }}–{{ $this->documents->lastItem() }} dari {{ $this->documents->total() }} berkas
            @else
                Menampilkan 0 berkas
            @endif
        </p>
        {{ $this->documents->links() }}
    </footer>

    @once
        <style>
            .supporting-documents-toolbar { grid-template-columns: minmax(16rem, 2fr) repeat(2, minmax(10rem, 1fr)) auto; }
            .supporting-documents-pagination { display: flex; align-items: center; justify-content: space-between; gap: 1rem; color: rgb(75 85 99); font-size: .75rem; }
            .supporting-documents-pagination p { margin: 0; }
            .supporting-documents-pagination nav { margin-left: auto; }
            @media (max-width: 767px) {
                .supporting-documents-toolbar { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .supporting-documents-toolbar > :first-child { grid-column: 1 / -1; }
                .supporting-documents-pagination { align-items: flex-start; flex-direction: column; }
                .supporting-documents-pagination nav { margin-left: 0; }
            }
        </style>
    @endonce
</x-filament-panels::page>
