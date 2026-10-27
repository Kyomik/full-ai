<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CulturalEventSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgencySeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
        ]);

        $agencies = Agency::query()->where('email', 'like', 'instansi.demo%@example.test')->orderBy('email')->get();
        $categories = Category::query()->where('name', 'like', 'Demo:%')->orderBy('id')->get();
        $locations = Location::query()->where('name', 'like', 'Demo:%')->orderBy('id')->get();
        $superAdminId = User::query()->where('role', 'super_admin')->orderBy('id')->value('id');
        $poster = \Database\Seeders\Support\DemoMedia::placeholder();
        $titles = [
            'Pekan Musik Tradisional Tana Luwu', 'Pentas Tari Bunga Masamba', 'Pameran Tenun dan Kerajinan Luwu',
            'Festival Kuliner Pesisir Palopo', 'Malam Teater La Galigo', 'Jelajah Sejarah Kedatuan Luwu',
            'Pekan Sastra dan Cerita Rakyat', 'Festival Permainan Tradisional Anak', 'Pameran Seni Rupa Wara',
            'Lomba Fotografi Warisan Budaya', 'Parade Busana Adat Tana Luwu', 'Ritual Adat dan Tradisi Luwu',
            'Pemutaran Film Dokumenter Palopo', 'Pasar Kreatif Batara Guru', 'Kelas Terbuka Budaya Lokal',
            'Tur Sejarah Kota Palopo', 'Gelar Olahraga Tradisional', 'Festival Seni Pesisir Teluk Bone',
            'Panggung Komunitas Kreatif Palopo', 'Pagelaran Musik dan Tari Luwu',
        ];
        $statuses = ['approved', 'pending', 'approved', 'rejected', 'approved'];

        foreach ($titles as $index => $title) {
            $number = $index + 1;
            $status = $statuses[$index % count($statuses)];
            $location = $locations[$index % $locations->count()];
            $startsOn = today()->addDays(($index * 2) + 1);
            $createdAt = now()->subDays(30 - $index);

            CulturalEvent::query()->updateOrCreate(
                ['slug' => 'demo-' . Str::slug($title)],
                [
                    'agency_id' => $agencies[($index * 3) % $agencies->count()]->id,
                    'category_id' => $categories[($index * 5) % $categories->count()]->id,
                    'location_id' => $location->id,
                    'title' => "Demo: {$title}",
                    'theme' => 'Warisan dan kreativitas budaya Tana Luwu',
                    'description' => "Kegiatan simulasi nomor {$number} untuk menampilkan informasi acara, jadwal, lokasi, dan proses verifikasi di portal budaya Kota Palopo.",
                    'starts_on' => $startsOn,
                    'ends_on' => $startsOn->copy()->addDay(),
                    'starts_at' => sprintf('%02d:00:00', 9 + ($index % 9)),
                    'venue' => $location->name,
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'poster' => $poster,
                    'status' => $status,
                    'review_note' => $status === 'rejected' ? 'Data simulasi perlu dilengkapi sebelum dapat disetujui.' : null,
                    'reviewed_by' => $status === 'pending' ? null : $superAdminId,
                    'reviewed_at' => $status === 'pending' ? null : $createdAt->copy()->addHours(5),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ],
            );
        }

        $this->command?->info('20 event simulasi berhasil disiapkan.');
    }
}
