<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\EventDocument;
use App\Models\GalleryItem;
use App\Models\Location;
use Database\Seeders\Support\DemoMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EventDetailDemoSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->updateOrCreate(
            ['name' => 'Demo Detail: Festival Budaya'],
            ['description' => 'Kategori contoh untuk pratinjau halaman detail event.', 'status' => 'active'],
        );

        $agency = Agency::query()->updateOrCreate(
            ['email' => 'demo.detail.event@example.test'],
            [
                'name' => 'Simulasi: Sanggar Budaya Tana Luwu',
                'type' => 'Komunitas Budaya',
                'phone' => '081234567890',
                'address' => 'Kota Palopo, Sulawesi Selatan',
                'status' => 'active',
            ],
        );

        $location = Location::query()->updateOrCreate(
            ['name' => 'Simulasi: Pelataran Istana Kedatuan Luwu'],
            [
                'type' => 'building',
                'address' => 'Jalan Andi Djemma, Kota Palopo, Sulawesi Selatan',
                'latitude' => -2.9925,
                'longitude' => 120.1964,
                'status' => 'active',
            ],
        );

        $poster = DemoMedia::placeholder();
        $startsOn = today()->addDays(21);

        $event = CulturalEvent::query()->updateOrCreate(
            ['slug' => 'simulasi-festival-budaya-tana-luwu'],
            [
                'agency_id' => $agency->id,
                'category_id' => $category->id,
                'location_id' => $location->id,
                'title' => 'Simulasi: Festival Budaya Tana Luwu',
                'theme' => 'Merawat warisan, merayakan kreativitas generasi',
                'description' => "Festival Budaya Tana Luwu menghadirkan ruang temu bagi seniman, komunitas, dan masyarakat untuk menikmati kekayaan budaya daerah. Acara simulasi ini disiapkan untuk memperlihatkan tampilan lengkap halaman detail festival.\n\nPengunjung dapat menyaksikan pertunjukan musik dan tari tradisional, mengenal kerajinan lokal, serta mengikuti tur cerita sejarah yang dipandu oleh komunitas budaya setempat. Berbagai kegiatan dirancang agar keluarga dan pengunjung dari segala usia dapat ikut berpartisipasi.",
                'starts_on' => $startsOn,
                'ends_on' => $startsOn->copy()->addDays(2),
                'starts_at' => '09:00:00',
                'venue' => 'Pelataran Istana Kedatuan Luwu',
                'address' => 'Jalan Andi Djemma, Kota Palopo, Sulawesi Selatan',
                'latitude' => -2.9925,
                'longitude' => 120.1964,
                'poster' => $poster,
                'status' => 'approved',
                'review_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => now(),
            ],
        );

        $documents = [
            'Rundown Festival Budaya Tana Luwu.txt' => "RUNDOWN FESTIVAL BUDAYA TANA LUWU\n\nHari pertama: pembukaan dan parade budaya.\nHari kedua: lokakarya kerajinan dan pertunjukan seni.\nHari ketiga: panggung komunitas dan penutupan.\n\nWaktu kegiatan: 09.00–21.00 WITA.",
            'Panduan Pengunjung.txt' => "PANDUAN PENGUNJUNG\n\nArea acara dibuka mulai pukul 09.00 WITA.\nSilakan menjaga kebersihan dan mengikuti arahan petugas.\nArea pertunjukan dapat diakses oleh pengunjung umum.",
            'Informasi Kegiatan.txt' => "INFORMASI KEGIATAN\n\nPenyelenggara: Sanggar Budaya Tana Luwu.\nLokasi: Pelataran Istana Kedatuan Luwu, Kota Palopo.\nSemua informasi dalam berkas ini merupakan data simulasi.",
        ];

        foreach ($documents as $name => $contents) {
            $path = 'demo/fiktif/event-detail/' . str($name)->slug() . '.txt';
            Storage::disk('public')->put($path, $contents);

            EventDocument::query()->updateOrCreate(
                ['cultural_event_id' => $event->id, 'name' => $name],
                ['file_path' => $path],
            );
        }

        $uploaderId = \App\Models\User::query()->where('role', 'super_admin')->orderBy('id')->value('id');
        $gallery = [
            'Pembukaan dan parade budaya',
            'Pertunjukan tari tradisional',
            'Pameran kerajinan lokal',
            'Panggung komunitas seni',
            'Suasana pengunjung festival',
            'Musik tradisional Tana Luwu',
        ];

        foreach ($gallery as $index => $title) {
            $publishedAt = now()->subMinutes(count($gallery) - $index - 1);

            GalleryItem::query()->updateOrCreate(
                ['cultural_event_id' => $event->id, 'title' => 'Simulasi: ' . $title],
                [
                    'uploaded_by_id' => $uploaderId,
                    'file_path' => DemoMedia::galleryPreview($index),
                    'type' => 'photo',
                    'is_published' => true,
                    'created_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                ],
            );
        }

        $this->command?->info('Data simulasi detail event siap: /festival/simulasi-festival-budaya-tana-luwu');
    }
}
