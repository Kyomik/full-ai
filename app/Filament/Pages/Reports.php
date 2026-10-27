<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AgencyResource;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CulturalEventResource;
use App\Filament\Resources\GalleryItemResource;
use App\Filament\Resources\LocationResource;
use App\Services\ReportCatalog;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'Data Laporan';
    protected static string $view = 'filament.pages.reports';

    public string $reportType = '';
    public string $periodFrom = '';
    public string $periodUntil = '';
    public string $statusFilter = '';

    public function mount(): void
    {
        $this->periodFrom = today()->startOfMonth()->toDateString();
        $this->periodUntil = today()->endOfMonth()->toDateString();
    }

    public function getTitle(): string
    {
        return 'Data Laporan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola dan unduh laporan data festival & event budaya.';
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.reports-header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'Dashboard',
            'Laporan',
        ];
    }

    protected function getViewData(): array
    {
        $reports = app(ReportCatalog::class)->reports($this->periodFrom ?: null, $this->periodUntil ?: null, auth()->id(), $this->reportType ?: null);
        $reports = array_values(array_filter($reports, fn (array $report): bool =>
            $this->statusFilter === '' || $report['status'] === $this->statusFilter
        ));

        return ['reports' => $reports];
    }

    public function resetFilters(): void
    {
        $this->reportType = '';
        $this->periodFrom = today()->startOfMonth()->toDateString();
        $this->periodUntil = today()->endOfMonth()->toDateString();
        $this->statusFilter = '';
    }

    public function downloadReport(string $type)
    {
        return app(ReportCatalog::class)->download($type, $this->periodFrom ?: null, $this->periodUntil ?: null);
    }

    public function downloadAllReports()
    {
        $types = array_column($this->getViewData()['reports'], 'type');

        return app(ReportCatalog::class)->downloadAll($types, $this->periodFrom ?: null, $this->periodUntil ?: null);
    }

    public function reportUrl(string $type): string
    {
        return match ($type) {
            'events' => CulturalEventResource::getUrl('index'),
            'agencies' => AgencyResource::getUrl('index'),
            'categories' => CategoryResource::getUrl('index'),
            'articles' => ArticleResource::getUrl('index'),
            'gallery' => GalleryItemResource::getUrl('index'),
            'locations' => LocationResource::getUrl('index'),
            'notifications' => Notifications::getUrl(),
            default => '#',
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadAllReports')
                ->label('Unduh Semua Laporan')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => $this->downloadAllReports()),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }
}
