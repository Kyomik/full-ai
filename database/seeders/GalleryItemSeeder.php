<?php

namespace Database\Seeders;

use App\Models\CulturalEvent;
use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CulturalEventSeeder::class);

        $uploader = User::query()->where('role', 'super_admin')->orderBy('id')->first();

        if (! $uploader) {
            throw new \RuntimeException('GalleryItemSeeder membutuhkan akun Super Admin yang sudah ada. Seeder tidak membuat pengguna.');
        }

        $events = CulturalEvent::query()
            ->where('slug', 'like', 'demo-%')
            ->where('status', 'approved')
            ->orderBy('starts_on')
            ->get();
        $image = \Database\Seeders\Support\DemoMedia::placeholder();

        for ($index = 0; $index < 20; $index++) {
            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);

            GalleryItem::query()->updateOrCreate(
                ['title' => "Demo: Dokumentasi Budaya Palopo {$number}"],
                [
                    'cultural_event_id' => $events[$index % $events->count()]->id,
                    'uploaded_by_id' => $uploader->id,
                    'file_path' => $image,
                    'type' => 'photo',
                    'is_published' => $index % 9 !== 8,
                ],
            );
        }

        $this->command?->info('20 foto galeri simulasi berhasil disiapkan.');
    }
}
