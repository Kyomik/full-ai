<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Seni & Budaya', 'Pariwisata', 'Ekonomi Kreatif', 'Pendidikan', 'Olahraga', 'Lainnya',
            'Demo: Musik Tradisional', 'Demo: Tari Tradisional', 'Demo: Teater Rakyat', 'Demo: Kerajinan Lokal',
            'Demo: Kuliner Tradisional', 'Demo: Sejarah dan Cagar Budaya', 'Demo: Sastra Daerah', 'Demo: Permainan Tradisional',
            'Demo: Pameran Seni Rupa', 'Demo: Fotografi Budaya', 'Demo: Busana Adat', 'Demo: Ritual dan Tradisi',
            'Demo: Film dan Dokumenter', 'Demo: Ekonomi Kreatif', 'Demo: Edukasi Budaya', 'Demo: Wisata Sejarah',
            'Demo: Olahraga Tradisional', 'Demo: Festival Pesisir', 'Demo: Seni Pertunjukan', 'Demo: Komunitas Kreatif',
        ];

        foreach ($names as $index => $name) {
            if (! str_starts_with($name, 'Demo:')) {
                Category::query()->firstOrCreate(['name' => $name]);

                continue;
            }

            Category::query()->updateOrCreate(
                ['name' => $name],
                [
                    'description' => 'Kategori simulasi untuk demonstrasi data budaya Palopo (' . ($index - 5) . ').',
                    'status' => $index >= 24 ? 'inactive' : 'active',
                ],
            );
        }
    }
}
