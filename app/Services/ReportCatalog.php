<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\Article;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function reports(?string $from, ?string $until): array
    {
        $definitions = $this->definitions();

        foreach ($definitions as $type => &$definition) {
            $definition['type'] = $type;
            $definition['count'] = $this->query($type, $from, $until)->count();
        }

        return array_values($definitions);
    }

    public function download(string $type, ?string $from, ?string $until): StreamedResponse
    {
        abort_unless(isset($this->definitions()[$type]), 404);

        $definition = $this->definitions()[$type];
        $query = $this->query($type, $from, $until);

        return response()->streamDownload(function () use ($query, $definition, $type): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, array_values($definition['fields']));

            $query->chunkById(200, function ($records) use ($stream, $type, $definition): void {
                foreach ($records as $record) {
                    $values = $this->recordValues($record, $type);
                    fputcsv($stream, array_map(fn (string $field): mixed => $values[$field] ?? '', array_keys($definition['fields'])));
                }
            });

            fclose($stream);
        }, $this->filename($type, $from, $until), ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** @param array<int, string> $types */
    public function downloadAll(array $types, ?string $from, ?string $until): StreamedResponse
    {
        $definitions = $this->definitions();
        $types = array_values(array_filter($types, fn (string $type): bool => isset($definitions[$type])));

        return response()->streamDownload(function () use ($types, $from, $until, $definitions): void {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, ['Jenis Laporan', 'Nomor Data', 'Kolom', 'Nilai']);

            foreach ($types as $type) {
                $rowNumber = 0;
                $this->query($type, $from, $until)->chunkById(200, function ($records) use ($stream, $type, $definitions, &$rowNumber): void {
                    foreach ($records as $record) {
                        $rowNumber++;
                        $values = $this->recordValues($record, $type);

                        foreach ($definitions[$type]['fields'] as $field => $label) {
                            fputcsv($stream, [
                                $definitions[$type]['title'],
                                $rowNumber,
                                $label,
                                is_scalar($values[$field] ?? null) ? ($values[$field] ?? '') : json_encode($values[$field], JSON_UNESCAPED_UNICODE),
                            ]);
                        }
                    }
                });
            }

            fclose($stream);
        }, $this->filename('semua-laporan', $from, $until), ['Content-Type' => 'text/csv; charset=UTF-8']);
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

        return $query
            ->when($from, fn ($query) => $query->whereDate($dateColumn, '>=', $from))
            ->when($until, fn ($query) => $query->whereDate($dateColumn, '<=', $until));
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
        return sprintf('laporan-%s-%s-sampai-%s.csv', $type, $from ?: 'awal', $until ?: 'sekarang');
    }
}
