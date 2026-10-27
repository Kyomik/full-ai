<?php

namespace Database\Seeders;

use App\Models\CulturalEvent;
use App\Models\EventDocument;
use Illuminate\Database\Seeder;

class EventDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CulturalEventSeeder::class);

        $events = CulturalEvent::query()
            ->where('slug', 'like', 'demo-%')
            ->orderBy('id')
            ->take(20)
            ->get();
        $file = \Database\Seeders\Support\DemoMedia::placeholder();

        foreach ($events as $index => $event) {
            EventDocument::query()->updateOrCreate(
                [
                    'cultural_event_id' => $event->id,
                    'name' => 'Dokumen simulasi ' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                ],
                ['file_path' => $file],
            );
        }

        $this->command?->info('20 dokumen pendukung simulasi berhasil disiapkan.');
    }
}
