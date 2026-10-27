<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('role', 'super_admin')->orderBy('id')->first();

        if (! $author) {
            $this->command?->warn('DemoContentSeeder dilewati: belum ada Super Admin.');

            return;
        }

        $this->seedDemoImages();
        $this->seedArticles($author);
        $this->seedGallery($author);
        $this->seedLocations();
        $this->seedNotifications();
    }

    private function seedDemoImages(): void
    {
        $source = public_path('images/palopo-seal.jpeg');

        if (! File::exists($source)) {
            return;
        }

        foreach (['demo/articles', 'demo/gallery'] as $directory) {
            File::ensureDirectoryExists(storage_path('app/public/' . $directory));

            for ($index = 1; $index <= 6; $index++) {
                $target = storage_path("app/public/{$directory}/item-{$index}.jpeg");

                if (! File::exists($target)) {
                    File::copy($source, $target);
                }
            }
        }
    }

    private function seedArticles(User $author): void
    {
        $articles = [
            ['title' => 'Palopo Siapkan Agenda Budaya Tahun Ini', 'excerpt' => 'Rangkaian kegiatan budaya Kota Palopo kembali disiapkan untuk masyarakat.', 'days' => 2],
            ['title' => 'Ragam Seni Tradisi Tampil di Pusat Kota', 'excerpt' => 'Pertunjukan seni tradisi menjadi ruang bertemu bagi komunitas dan warga.', 'days' => 5],
            ['title' => 'Komunitas Lokal Dorong Pelestarian Budaya', 'excerpt' => 'Komunitas budaya mengajak generasi muda mengenal warisan daerah.', 'days' => 9],
            ['title' => 'Pameran Fotografi Mengangkat Cerita Palopo', 'excerpt' => 'Kisah kota dan masyarakat dituangkan melalui karya fotografi.', 'days' => 14],
            ['title' => 'Kolaborasi Kreatif untuk Festival Daerah', 'excerpt' => 'Pelaku kreatif dan pemerintah daerah memperkuat kolaborasi acara budaya.', 'days' => 20],
            ['title' => 'Kalender Budaya Palopo Diperbarui', 'excerpt' => 'Informasi agenda festival dan event budaya kini tersedia dalam satu portal.', 'days' => 28],
        ];

        foreach ($articles as $index => $article) {
            Article::updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'user_id' => $author->id,
                    'title' => $article['title'],
                    'excerpt' => $article['excerpt'],
                    'body' => $article['excerpt'] . "\n\nArtikel dummy untuk pengujian halaman berita dan portal publik.",
                    'image' => "demo/articles/item-" . ($index + 1) . '.jpeg',
                    'published_at' => now()->subDays($article['days']),
                ],
            );
        }
    }

    private function seedGallery(User $author): void
    {
        $events = CulturalEvent::query()->where('status', 'approved')->orderBy('id')->take(6)->get();

        foreach ($events as $index => $event) {
            GalleryItem::updateOrCreate(
                ['title' => 'Dokumentasi ' . $event->title],
                [
                    'cultural_event_id' => $event->id,
                    'title' => 'Dokumentasi ' . $event->title,
                    'file_path' => 'demo/gallery/item-' . (($index % 6) + 1) . '.jpeg',
                    'type' => 'photo',
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedLocations(): void
    {
        $locations = [
            ['name' => 'Lapangan Pancasila', 'type' => 'open_space', 'address' => 'Jl. Andi Djemma, Kota Palopo', 'latitude' => -2.9912, 'longitude' => 120.1975],
            ['name' => 'Gedung SaokotaE', 'type' => 'building', 'address' => 'Jl. Veteran, Kota Palopo', 'latitude' => -2.9971, 'longitude' => 120.1938],
            ['name' => 'Pantai Labombo', 'type' => 'natural_tourism', 'address' => 'Kota Palopo', 'latitude' => -3.0034, 'longitude' => 120.1642],
            ['name' => 'Kawasan City Walk', 'type' => 'venue', 'address' => 'Pusat Kota Palopo', 'latitude' => -2.9998, 'longitude' => 120.1956],
            ['name' => 'Benteng Rotterdam Palopo', 'type' => 'heritage', 'address' => 'Kota Palopo', 'latitude' => -2.9876, 'longitude' => 120.1941],
            ['name' => 'Ruang Kreatif Wara', 'type' => 'building', 'address' => 'Kecamatan Wara, Kota Palopo', 'latitude' => -3.0102, 'longitude' => 120.2021],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name']],
                [...$location, 'status' => 'active'],
            );
        }
    }

    private function seedNotifications(): void
    {
        $recipients = User::query()->where('role', 'super_admin')->where('status', 'active')->get();
        $notifications = [
            ['title' => 'Pengajuan event baru', 'message' => 'Ada pengajuan event baru yang menunggu verifikasi.', 'category' => 'submission'],
            ['title' => 'Berita berhasil dipublikasikan', 'message' => 'Berita dummy telah tersedia di portal publik.', 'category' => 'information'],
            ['title' => 'Pengingat agenda budaya', 'message' => 'Periksa kembali agenda festival yang akan datang.', 'category' => 'reminder'],
            ['title' => 'Data galeri diperbarui', 'message' => 'Dokumentasi galeri dummy telah ditambahkan ke sistem.', 'category' => 'system'],
        ];

        foreach ($recipients as $recipient) {
            foreach ($notifications as $index => $notification) {
                $seedKey = "demo-notification-{$recipient->id}-" . ($index + 1);
                $createdAt = now()->subDays($index);
                $hash = sha1($seedKey);
                $notificationId = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-4' . substr($hash, 13, 3) . '-8' . substr($hash, 17, 3) . '-' . substr($hash, 20, 12);

                DB::table('notifications')->updateOrInsert(
                    [
                        'id' => $notificationId,
                        'notifiable_type' => User::class,
                        'notifiable_id' => $recipient->id,
                    ],
                    [
                        'type' => 'App\\Notifications\\AdminBroadcastNotification',
                        'data' => json_encode([
                            'format' => 'filament',
                            'title' => $notification['title'],
                            'body' => $notification['message'],
                            'message' => $notification['message'],
                            'category' => $notification['category'],
                            'sender_name' => 'Super Admin Demo',
                            'seed_key' => $seedKey,
                            'created_at' => $createdAt->toIso8601String(),
                        ], JSON_THROW_ON_ERROR),
                        'read_at' => $index === 3 ? $createdAt : null,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ],
                );
            }
        }
    }
}
