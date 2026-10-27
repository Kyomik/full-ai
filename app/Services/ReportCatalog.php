<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Article;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\ReportDownload;
use App\Models\User;
use App\Exports\ReportSheet;
use App\Exports\ReportsWorkbook;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ReportCatalog
{
    /** @return array<string, array<string, mixed>> */
    public function definitions(): array
    {
        return [
            'events' => [
                'title' => 'Laporan Festival & Event',
                'summary' => 'Rekap data festival & event',
                'description' => 'Berisi rekapitulasi data festival dan event yang telah diselenggarakan pada periode tersebut.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'title' => 'Judul Event', 'status' => 'Status', 'starts_on' => 'Mulai', 'ends_on' => 'Selesai', 'created_at' => 'Dibuat Pada'],
            ],
            'agencies' => [
                'title' => 'Laporan Instansi',
                'summary' => 'Rekap data instansi terdaftar',
                'description' => 'Berisi daftar instansi yang terdaftar dan telah diverifikasi.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'name' => 'Nama Instansi', 'type' => 'Jenis', 'email' => 'Email', 'status' => 'Status', 'created_at' => 'Dibuat Pada'],
            ],
            'categories' => [
                'title' => 'Laporan Kategori',
                'summary' => 'Rekap data kategori',
                'description' => 'Berisi daftar kategori festival & event yang tersedia.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'name' => 'Nama Kategori', 'description' => 'Deskripsi', 'status' => 'Status', 'created_at' => 'Dibuat Pada'],
            ],
            'articles' => [
                'title' => 'Laporan Berita',
                'summary' => 'Rekap data berita',
                'description' => 'Berisi daftar berita yang telah dipublikasikan pada periode tersebut.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'title' => 'Judul Berita', 'excerpt' => 'Ringkasan', 'published_at' => 'Tanggal Publikasi', 'created_at' => 'Dibuat Pada'],
            ],
            'gallery' => [
                'title' => 'Laporan Galeri',
                'summary' => 'Rekap data galeri',
                'description' => 'Berisi rekapitulasi data galeri foto dan video.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'title' => 'Judul', 'type' => 'Jenis', 'is_published' => 'Dipublikasikan', 'created_at' => 'Dibuat Pada'],
            ],
            'locations' => [
                'title' => 'Laporan Lokasi',
                'summary' => 'Rekap data lokasi',
                'description' => 'Berisi daftar lokasi penyelenggaraan festival & event.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'name' => 'Nama Lokasi', 'type' => 'Kategori', 'address' => 'Alamat', 'status' => 'Status', 'created_at' => 'Dibuat Pada'],
            ],
            'notifications' => [
                'title' => 'Laporan Notifikasi',
                'summary' => 'Rekap data notifikasi',
                'description' => 'Berisi daftar notifikasi yang telah dikirim kepada pengguna.',
                'date_column' => 'created_at',
                'fields' => ['id' => 'ID', 'title' => 'Judul Notifikasi', 'message' => 'Isi Notifikasi', 'sender_name' => 'Dikirim Oleh', 'created_at' => 'Tanggal Kirim'],
            ],
        ];
    }

    /**
     * @param  string|null  $onlyType  Batasi hanya satu jenis laporan agar tidak menghitung yang tidak ditampilkan.
     */
    public function reports(?string $from, ?string $until, ?int $userId = null, ?string $onlyType = null): array
    {
        $definitions = $this->definitions();

        if ($onlyType !== null && $onlyType !== '') {
            $definitions = array_intersect_key($definitions, [$onlyType => true]);
        }

        // Satu query untuk seluruh status unduhan, bukan satu query per jenis laporan.
        $downloads = $userId && $definitions !== []
            ? ReportDownload::query()
                ->where('user_id', $userId)
                ->whereIn('report_type', array_keys($definitions))
                ->where('period_from', $from)
                ->where('period_until', $until)
                ->orderByDesc('updated_at')
                ->get()
                ->unique('report_type')
                ->keyBy('report_type')
            : collect();

        foreach ($definitions as $type => &$definition) {
            $download = $downloads->get($type);

            $definition['type'] = $type;
            $definition['count'] = $this->query($type, $from, $until)->count();
            $definition['status'] = $download?->status ?? 'not_downloaded';
            $definition['downloaded_at'] = $download?->downloaded_at;
            $definition['status_label'] = match ($definition['status']) {
                'processing' => 'Diproses',
                'completed' => 'Selesai',
                'failed' => 'Gagal',
                default => 'Belum Diunduh',
            };
        }

        return array_values($definitions);
    }

    public function download(string $type, ?string $from, ?string $until)
    {
        abort_unless(isset($this->definitions()[$type]), 404);

        $definition = $this->definitions()[$type];
        $sheet = new ReportSheet(
            sheetTitle: $this->sheetTitle($definition['title']),
            fields: $definition['fields'],
            rows: $this->rows($type, $from, $until),
        );

        $download = $this->beginDownload($type, $from, $until);

        try {
            $response = Excel::download($sheet, $this->filename($type, $from, $until));
            $download->update(['status' => 'completed', 'downloaded_at' => now()]);

            return $response;
        } catch (\Throwable $exception) {
            $download->update(['status' => 'failed']);
            throw $exception;
        }
    }

    /** @param array<int, string> $types */
    public function downloadAll(array $types, ?string $from, ?string $until)
    {
        $definitions = $this->definitions();
        $types = array_values(array_filter($types, fn (string $type): bool => isset($definitions[$type])));

        $sheets = array_map(fn (string $type): ReportSheet => new ReportSheet(
            sheetTitle: $this->sheetTitle($definitions[$type]['title']),
            fields: $definitions[$type]['fields'],
            rows: $this->rows($type, $from, $until),
        ), $types);

        $downloads = collect($types)->mapWithKeys(fn (string $type): array => [
            $type => $this->beginDownload($type, $from, $until),
        ]);

        try {
            $response = Excel::download(new ReportsWorkbook($sheets), $this->filename('semua-laporan', $from, $until));
            $downloads->each(fn (ReportDownload $download) => $download->update(['status' => 'completed', 'downloaded_at' => now()]));

            return $response;
        } catch (\Throwable $exception) {
            $downloads->each(fn (ReportDownload $download) => $download->update(['status' => 'failed']));
            throw $exception;
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function rows(string $type, ?string $from, ?string $until): array
    {
        return $this->query($type, $from, $until)
            ->get()
            ->map(fn ($record): array => $this->recordValues($record, $type))
            ->all();
    }

    private function beginDownload(string $type, ?string $from, ?string $until): ReportDownload
    {
        return ReportDownload::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'report_type' => $type,
                'period_from' => $from,
                'period_until' => $until,
            ],
            [
                'status' => 'processing',
                'downloaded_at' => null,
            ],
        );
    }

    private function query(string $type, ?string $from, ?string $until): EloquentBuilder|QueryBuilder
    {
        $query = match ($type) {
            'events' => CulturalEvent::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'agencies' => Agency::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'categories' => Category::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'articles' => Article::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'gallery' => GalleryItem::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'locations' => Location::query()->select(array_keys($this->definitions()[$type]['fields'])),
            'notifications' => DB::table('notifications')
                ->select(['id', 'data', 'created_at'])
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', auth()->id())
                ->where('type', AdminBroadcastNotification::class),
            default => abort(404),
        };

        $dateColumn = $this->definitions()[$type]['date_column'];

        $start = $this->boundary($from, false);
        $end = $this->boundary($until, true);

        // Rentang langsung pada kolom (bukan DATE(kolom)) supaya indeks bisa dipakai.
        return $query
            ->when($start, fn ($query) => $query->where($dateColumn, '>=', $start))
            ->when($end, fn ($query) => $query->where($dateColumn, '<=', $end));
    }

    private function boundary(?string $date, bool $endOfDay): ?Carbon
    {
        if (! $date) {
            return null;
        }

        try {
            $parsed = Carbon::parse($date);
        } catch (\Throwable) {
            return null;
        }

        return $endOfDay ? $parsed->endOfDay() : $parsed->startOfDay();
    }

    private function recordValues(object $record, string $type): array
    {
        if ($type === 'notifications') {
            $data = json_decode($record->data ?? '{}', true) ?: [];

            return [
                'id' => $record->id,
                'title' => $data['title'] ?? '',
                'message' => $data['message'] ?? '',
                'sender_name' => $data['sender_name'] ?? '',
                'created_at' => $record->created_at,
            ];
        }

        return method_exists($record, 'getAttributes') ? $record->getAttributes() : get_object_vars($record);
    }

    private function filename(string $type, ?string $from, ?string $until): string
    {
        return sprintf('laporan-%s-%s-sampai-%s.xlsx', $type, $from ?: 'awal', $until ?: 'sekarang');
    }

    private function sheetTitle(string $title): string
    {
        $title = preg_replace('/[\\\\\\/?*\\[\\]:]/', '-', $title) ?: 'Laporan';

        return Str::limit($title, 31, '');
    }
}
