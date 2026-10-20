<?php

namespace App\Filament\Widgets;

use App\Models\Agency;
use App\Models\CulturalEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PortalOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $events = CulturalEvent::query();
        if ($user->role === 'agency_admin') $events->where('agency_id', $user->agency_id);

        return [
            Stat::make('Total Pengajuan', (clone $events)->count())
                ->description('Seluruh pengajuan event')
                ->icon('heroicon-o-document'),
            Stat::make('Event Disetujui', (clone $events)->where('status', 'approved')->count())
                ->description('Event telah dipublikasikan')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Instansi Terdaftar', Agency::query()->where('status', 'active')->count())
                ->description('Total instansi aktif')
                ->icon('heroicon-o-building-office-2')
                ->color('info'),
            Stat::make('Berita', \App\Models\Article::query()->whereNotNull('published_at')->count())
                ->description('Berita telah dipublikasikan')
                ->icon('heroicon-o-newspaper')
                ->color('warning'),
            Stat::make('Kategori', \App\Models\Category::query()->count())
                ->description('Total kategori event')
                ->icon('heroicon-o-tag')
                ->color('gray'),
        ];
    }
}
