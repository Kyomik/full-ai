<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Announcement::create([
                'title' => "Pengumuman {$i}",
                'body' => "Konten pengumuman contoh ke-{$i}.",
                'published_on' => now()->subDays($i),
                'is_published' => true,
            ]);
        }
    }
}
