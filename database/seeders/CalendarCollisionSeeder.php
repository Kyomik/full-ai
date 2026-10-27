<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Category;
use App\Models\CulturalEvent;
use App\Models\Location;
use Illuminate\Database\Seeder;

class CalendarCollisionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AgencySeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
        ]);

        $agency = Agency::query()->where('email', 'instansi.demo01@example.test')->firstOrFail();
        $category = Category::query()->where('name', 'Demo: Musik Tradisional')->firstOrFail();
        $location = Location::query()->where('name', 'Demo: Lapangan Andi Djemma')->firstOrFail();
        $eventDate = today()->addDays(3);

        foreach ([
            'Pentas Musik Bambu',
            'Lokakarya Alat Musik Tradisional',
            'Diskusi Sejarah Musik Tana Luwu',
            'Pertunjukan Musik Komunitas',
            'Pengenalan Irama Tradisional untuk Anak',
        ] as $index => $title) {
            $number = $index + 1;

            CulturalEvent::query()->updateOrCreate(
                ['slug' => "simulasi-kalender-tanggal-sama-{$number}"],
                [
                    'agency_id' => $agency->id,
                    'category_id' => $category->id,
                    'location_id' => $location->id,
                    'title' => "Simulasi Kalender: {$title}",
                    'theme' => 'Simulasi beberapa event pada satu tanggal',
                    'description' => 'Data simulasi untuk melihat tampilan kalender saat beberapa event memiliki tanggal yang sama.',
                    'starts_on' => $eventDate,
                    'ends_on' => $eventDate,
                    'starts_at' => sprintf('%02d:00:00', 9 + $index * 2),
                    'venue' => $location->name,
                    'address' => $location->address,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'status' => 'approved',
                    'review_note' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => now(),
                ],
            );
        }

        $this->command?->info('5 event simulasi pada tanggal ' . $eventDate->translatedFormat('d F Y') . ' berhasil disiapkan.');
    }
}
