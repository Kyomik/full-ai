<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AgencySeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            CulturalEventSeeder::class,
            CalendarCollisionSeeder::class,
            EventDocumentSeeder::class,
            ArticleSeeder::class,
            GalleryItemSeeder::class,
            BroadcastNotificationSeeder::class,
        ]);
    }
}
