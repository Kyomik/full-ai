<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\CategoryBreakdown;
use App\Filament\Widgets\EventSubmissionsChart;
use App\Filament\Widgets\PortalOverview;
use App\Filament\Widgets\RecentActivity;
use App\Filament\Widgets\RecentAnnouncements;
use App\Filament\Widgets\RecentSubmissions;
use App\Filament\Widgets\SubmissionStatusBreakdown;
use App\Filament\Widgets\UpcomingEvents;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        if (auth()->user()?->role === 'agency_admin') {
            return [
                PortalOverview::class,
                EventSubmissionsChart::class,
                SubmissionStatusBreakdown::class,
                RecentSubmissions::class,
                RecentAnnouncements::class,
            ];
        }

        return [
            PortalOverview::class,
            EventSubmissionsChart::class,
            RecentSubmissions::class,
            CategoryBreakdown::class,
            UpcomingEvents::class,
            RecentActivity::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 12,
        ];
    }

    public function getHeading(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        if (auth()->user()?->role === 'agency_admin') {
            return 'Selamat datang, Admin Penyelenggara. Kelola pengajuan festival & event budaya instansi Anda dengan mudah.';
        }

        return 'Selamat datang, ' . (auth()->user()?->name ?? 'Super Admin') . '!';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('currentDate')
                ->label(fn (): string => auth()->user()?->role === 'agency_admin'
                    ? now()->translatedFormat('l, d F Y')
                    : now()->translatedFormat('d F Y'))
                ->icon('heroicon-o-calendar')
                ->color('gray')
                ->disabled(),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }
}
