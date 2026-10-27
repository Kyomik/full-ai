<x-filament-panels::page class="reports-page resource-flat-table">
    <x-resource-filter-panel class="reports-filter-grid">
        <div class="resource-filter-field reports-filter-type">
            <label for="report-type">Jenis Laporan</label>
            <select id="report-type" wire:model.live="reportType">
                <option value="">Semua Jenis</option>
                <option value="events">Festival &amp; Event</option>
                <option value="agencies">Instansi</option>
                <option value="categories">Kategori</option>
                <option value="articles">Berita</option>
                <option value="gallery">Galeri</option>
                <option value="locations">Lokasi</option>
                <option value="notifications">Notifikasi</option>
            </select>
        </div>

        <div class="resource-filter-field reports-filter-period">
            <label>Periode</label>
            <x-resource-filter-date-range from="periodFrom" until="periodUntil" from-label="Tanggal awal periode" until-label="Tanggal akhir periode" />
        </div>

        <div class="resource-filter-field reports-filter-status">
            <label for="report-status">Status</label>
            <select id="report-status" wire:model.live="statusFilter">
                <option value="">Semua Status</option>
                <option value="not_downloaded">Belum Diunduh</option>
                <option value="processing">Diproses</option>
                <option value="completed">Selesai</option>
                <option value="failed">Gagal</option>
            </select>
        </div>

        <x-resource-filter-reset action="resetFilters" />
    </x-resource-filter-panel>

    <section class="reports-table-wrap" aria-label="Daftar laporan">
        <div class="overflow-x-auto">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Jenis Laporan</th>
                        <th scope="col">Periode</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Dibuat Pada</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $index => $report)
                        @php($reportIcon = match ($report['type']) {
                            'events' => 'heroicon-o-calendar-days',
                            'agencies' => 'heroicon-o-building-office-2',
                            'categories' => 'heroicon-o-folder',
                            'articles' => 'heroicon-o-document-text',
                            'gallery' => 'heroicon-o-photo',
                            'locations' => 'heroicon-o-map-pin',
                            default => 'heroicon-o-bell',
                        })
                        <tr wire:key="report-{{ $report['type'] }}">
                            <td class="reports-number">{{ $index + 1 }}</td>
                            <td>
                                <div class="report-title-cell">
                                    <x-filament::icon :icon="$reportIcon" />
                                    <div>
                                        <strong>{{ $report['title'] }}</strong>
                                        <p>{{ $report['summary'] }}</p>
                                        <small>{{ number_format($report['count']) }} data</small>
                                    </div>
                                </div>
                            </td>
                            <td class="reports-period">
                                {{ $periodFrom ? \Illuminate\Support\Carbon::parse($periodFrom)->translatedFormat('d M Y') : 'Semua' }}
                                <span>–</span>
                                {{ $periodUntil ? \Illuminate\Support\Carbon::parse($periodUntil)->translatedFormat('d M Y') : 'Sekarang' }}
                            </td>
                            <td class="reports-description">{{ $report['description'] }}</td>
                            <td class="reports-created">
                                @if ($report['downloaded_at'])
                                    {{ $report['downloaded_at']->translatedFormat('d M Y') }}<br>{{ $report['downloaded_at']->format('H:i') }} WITA
                                @else
                                    —
                                @endif
                            </td>
                            <td><span @class(['reports-status', 'is-processing' => $report['status'] === 'processing', 'is-completed' => $report['status'] === 'completed', 'is-failed' => $report['status'] === 'failed'])>{{ $report['status_label'] }}</span></td>
                            <td>
                                <div class="reports-actions">
                                    <a class="reports-action" href="{{ $this->reportUrl($report['type']) }}" aria-label="Lihat laporan {{ $report['title'] }}">
                                        <x-filament::icon icon="heroicon-o-eye" />
                                        <span>Lihat</span>
                                    </a>
                                    <button type="button" class="reports-action" wire:click="downloadReport('{{ $report['type'] }}')" wire:loading.attr="disabled" wire:loading.class="is-action-loading" wire:target="downloadReport('{{ $report['type'] }}')" aria-label="Unduh laporan {{ $report['title'] }}">
                                        <x-filament::icon icon="heroicon-o-arrow-down-tray" />
                                        <span>Unduh</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="reports-empty">Tidak ada jenis laporan yang cocok dengan filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="reports-table-footer">
            <p>Menampilkan {{ count($reports) ? '1 - ' . count($reports) : '0' }} dari {{ count($reports) }} data</p>
        </footer>
    </section>

    @once
        <style>
            .reports-page .fi-ta-header-toolbar { display: none; }
            @media (min-width: 1024px) {
                .reports-filter-grid { grid-template-columns: minmax(10rem, 1fr) minmax(14rem, 1.2fr) minmax(9rem, .9fr) auto; gap: 1.5rem; }
            }
            @media (max-width: 1023px) {
                .reports-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .reports-filter-period { grid-column: 1 / -1; }
                .reports-page .resource-filter-reset { justify-self: start; }
            }
            .reports-table-wrap { overflow: hidden; border: 0; background: transparent; }
            .reports-table { width: 100%; min-width: 66rem; border-collapse: collapse; text-align: left; color: rgb(17 24 39); font-size: .75rem; }
            .reports-table th { padding: .875rem .5rem; border-bottom: 1px solid rgb(156 163 175); font-weight: 700; white-space: nowrap; }
            .reports-table td { padding: .875rem .5rem; border-bottom: 1px solid rgb(209 213 219); vertical-align: middle; }
            .reports-number { width: 3rem; }
            .report-title-cell { display: flex; min-width: 13rem; align-items: center; gap: .75rem; }
            .report-title-cell > svg { width: 1.375rem; height: 1.375rem; flex: none; }
            .report-title-cell strong { display: block; font-weight: 700; }
            .report-title-cell p { margin: .25rem 0 0; color: rgb(75 85 99); }
            .report-title-cell small { display: block; margin-top: .25rem; color: rgb(107 114 128); }
            .reports-period { min-width: 7rem; line-height: 1.55; white-space: nowrap; }
            .reports-period span { display: block; color: rgb(107 114 128); }
            .reports-description { min-width: 11rem; max-width: 15rem; line-height: 1.55; }
            .reports-created { min-width: 6.25rem; white-space: nowrap; }
            .reports-status { display: inline-flex; border: 1px solid rgb(156 163 175); border-radius: .25rem; padding: .2rem .45rem; color: rgb(75 85 99); font-size: .625rem; white-space: nowrap; }
            .reports-status.is-processing { border-color: rgb(180 83 9); color: rgb(180 83 9); }
            .reports-status.is-completed { border-color: rgb(21 128 61); color: rgb(21 128 61); }
            .reports-status.is-failed { border-color: rgb(185 28 28); color: rgb(185 28 28); }
            .reports-actions { display: flex; align-items: center; justify-content: flex-end; gap: .875rem; }
            .reports-action { display: inline-flex; min-width: 2.5rem; flex-direction: column; align-items: center; gap: .125rem; border: 0; background: transparent; padding: 0; color: rgb(17 24 39); cursor: pointer; text-decoration: none; }
            .reports-action svg { width: 1.125rem; height: 1.125rem; }
            .reports-action span { font-size: .625rem; }
            .reports-action:hover { color: rgb(22 101 52); }
            .reports-empty { padding: 3rem 1rem !important; text-align: center; color: rgb(107 114 128); }
            .reports-table-footer { padding-top: 1rem; color: rgb(75 85 99); font-size: .75rem; }
            .reports-table-footer p { margin: 0; }
            .dark .reports-table { color: rgb(243 244 246); }
            .dark .reports-table th { border-bottom-color: rgb(107 114 128); }
            .dark .reports-table td { border-bottom-color: rgb(75 85 99); }
            .dark .report-title-cell p, .dark .report-title-cell small, .dark .reports-period span, .dark .reports-table-footer { color: rgb(156 163 175); }
            .dark .reports-action { color: rgb(243 244 246); }
        </style>
    @endonce
    <x-resource-flat-table-styles />
</x-filament-panels::page>
