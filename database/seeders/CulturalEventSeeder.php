<?php

namespace Database\Seeders;

use App\Models\CulturalEvent;
use App\Models\Agency;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CulturalEventSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there is at least one agency and category
        $agency = Agency::first() ?? Agency::factory()->create();
        $category = Category::first() ?? Category::factory()->create(['name' => 'Seni & Budaya']);

        for ($i = 1; $i <= 5; $i++) {
            CulturalEvent::create([
                'agency_id'      => $agency->id,
                'category_id'    => $category->id,
                'title'          => "Acara Budaya {$i}",
                'slug'           => Str::slug("Acara Budaya {$i}"),
                'theme'          => "Tema {$i}",
                'description'    => "Deskripsi acara budaya ke-{$i}.",
                'starts_on'      => now()->addDays($i),
                'ends_on'        => now()->addDays($i + 2),
                'starts_at'      => now()->addDays($i)->format('H:i:s'),
                'venue'          => "Venue {$i}",
                'address'        => "Alamat Venue {$i}",
                'province_code'  => '33',
                'regency_code'   => '3301',
                'district_code'  => '330101',
                'village_code'   => '33010101',
                'latitude'       => -3.0,
                'longitude'      => 120.0,
                'poster'         => "images/event{$i}.jpg",
                'status'         => 'approved',
                'review_note'    => null,
                'reviewed_by'    => null,
                'reviewed_at'    => null,
            ]);
        }
    }
}
