<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use Filament\Widgets\Widget;

class UpcomingEvents extends Widget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 5;
    protected static string $view = 'filament.widgets.upcoming-events';
    protected int|string|array $columnSpan = ['default' => 1, 'md' => 4];

    public static function canView(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    protected function getViewData(): array
    {
        return [
            'events' => CulturalEvent::query()
                ->with('agency')
                ->where('status', 'approved')
                ->whereDate('starts_on', '>=', today())
                ->orderBy('starts_on')
                ->limit(3)
                ->get(),
            'allEventsUrl' => \App\Filament\Resources\CulturalEventResource::getUrl('index'),
        ];
    }
}
