<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Notifications;
use Filament\Widgets\Widget;

class RecentAnnouncements extends Widget
{
    protected static ?int $sort = 5;
    protected static string $view = 'filament.widgets.recent-announcements';
    protected int|string|array $columnSpan = ['default' => 1, 'md' => 5];

    public static function canView(): bool
    {
        return auth()->user()?->role === 'agency_admin';
    }

    protected function getViewData(): array
    {
        return [
            'announcements' => auth()->user()->notifications()
                ->where('data->category', 'announcement')
                ->where(function ($query): void {
                    $query->whereNull('data->format')
                        ->orWhere('data->format', '!=', 'admin_broadcast_tracking');
                })
                ->latest()
                ->limit(3)
                ->get(),
            'allAnnouncementsUrl' => Notifications::getUrl(['filter' => 'announcement']),
        ];
    }
}
