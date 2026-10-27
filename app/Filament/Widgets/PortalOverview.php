<?php

namespace App\Filament\Widgets;

use App\Models\Agency;
use App\Models\CulturalEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PortalOverview extends BaseWidget
{
    protected static string $view = 'filament.widgets.portal-overview';

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return auth()->user()?->role === 'agency_admin' ? 4 : 5;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $events = CulturalEvent::query();
        if ($user->role === 'agency_admin') {
            $events->where('agency_id', $user->agency_id);
        }

        $eventCounts = $events
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalEvents = $eventCounts->sum();
        $approvedEvents = (int) ($eventCounts['approved'] ?? 0);

        if ($user->role === 'agency_admin') {
            return [
                Stat::make('Total Pengajuan', $totalEvents)
                    ->description('Semua pengajuan')
                    ->icon('heroicon-o-document'),
                Stat::make('Menunggu Verifikasi', (int) ($eventCounts['pending'] ?? 0))
                    ->description('Pengajuan')
                    ->icon('heroicon-o-clock'),
                Stat::make('Disetujui', $approvedEvents)
                    ->description('Pengajuan')
                    ->icon('heroicon-o-check-circle'),
                Stat::make('Ditolak', (int) ($eventCounts['rejected'] ?? 0))
                    ->description('Pengajuan')
                    ->icon('heroicon-o-x-circle'),
            ];
        }

        return [
            Stat::make('Total Pengajuan', $totalEvents)
                ->description('Seluruh pengajuan event')
                ->icon('heroicon-o-document'),
            Stat::make('Event Disetujui', $approvedEvents)
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
