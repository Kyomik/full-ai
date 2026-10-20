<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\CulturalEvent;
use Filament\Widgets\Widget;

class RecentActivity extends Widget
{
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
    ];
    protected static string $view = 'filament.widgets.recent-activity';

    protected function getViewData(): array
    {
        return [
            'activities' => collect()
                ->concat(CulturalEvent::query()->latest()->limit(4)->get()->map(fn ($event) => [
                    'title' => 'Pengajuan event "' . $event->title . '"',
                    'date' => $event->created_at,
                ]))
                ->concat(Article::query()->latest()->limit(2)->get()->map(fn ($article) => [
                    'title' => 'Menambahkan berita "' . $article->title . '"',
                    'date' => $article->created_at,
                ]))
                ->concat(Announcement::query()->latest()->limit(2)->get()->map(fn ($item) => [
                    'title' => 'Pengumuman "' . $item->title . '" diperbarui',
                    'date' => $item->created_at,
                ]))
                ->sortByDesc('date')
                ->take(5),
        ];
    }
}
