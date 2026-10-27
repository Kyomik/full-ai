<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CulturalEventResource;
use App\Models\Article;
use App\Models\CulturalEvent;
use Filament\Widgets\Widget;

class RecentActivity extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = ['default' => 1, 'md' => 4];
    protected static string $view = 'filament.widgets.recent-activity';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    protected function getViewData(): array
    {
        return [
            'allActivitiesUrl' => CulturalEventResource::getUrl('index'),
            'activities' => collect()
                ->concat(CulturalEvent::query()->select(['id', 'title', 'created_at'])->latest()->limit(5)->get()->map(fn ($event) => [
                    'title' => 'Pengajuan event "' . $event->title . '"',
                    'date' => $event->created_at,
                ]))
                ->concat(Article::query()->select(['id', 'title', 'created_at'])->latest()->limit(5)->get()->map(fn ($article) => [
                    'title' => 'Menambahkan berita "' . $article->title . '"',
                    'date' => $article->created_at,
                ]))
                ->sortByDesc('date')
                ->take(4),
        ];
    }
}
