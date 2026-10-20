<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\CulturalEvent;
use App\Models\User;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $event = CulturalEvent::first();
        if (! $event) {
            // Create a dummy event if none exists
            $event = CulturalEvent::factory()->create();
        }

        for ($i = 1; $i <= 5; $i++) {
            GalleryItem::create([
                'cultural_event_id' => $event->id,
                'uploaded_by_id'    => User::first()->id ?? User::factory()->create()->id,
                'title'             => "Galeri Item {$i}",
                'file_path'         => "images/gallery{$i}.jpg",
                'type'              => 'image',
                'is_published'      => true,
            ]);
        }
    }
}
