<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class EventSubmissionsChart extends ChartWidget
{
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Grafik Event per Bulan';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '240px';

    public function getColumnSpan(): int|string|array
    {
        return ['default' => 1, 'md' => auth()->user()?->role === 'agency_admin' ? 7 : 5];
    }

    public function getHeading(): ?string
    {
        return auth()->user()?->role === 'agency_admin' ? 'Statistik Pengajuan' : 'Grafik Event per Bulan';
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $query = CulturalEvent::query();
        if ($user?->role === 'agency_admin') {
            $query->where('agency_id', $user->agency_id);
        }

        $isAgencyAdmin = $user?->role === 'agency_admin';
        $dateColumn = $isAgencyAdmin ? 'created_at' : 'starts_on';
        $labels = collect(range(7, 0))->map(fn (int $offset) => now()->subMonths($offset)->startOfMonth());
        $monthlyCounts = $query
            ->whereBetween($dateColumn, [$labels->first(), now()->endOfMonth()])
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as month, COUNT(*) as aggregate")
            ->groupBy('month')
            ->pluck('aggregate', 'month');
        $counts = $labels->map(fn (Carbon $month): int => (int) ($monthlyCounts[$month->format('Y-m')] ?? 0));

        return [
            'datasets' => [[
                'label' => $isAgencyAdmin ? 'Pengajuan' : 'Event',
                'data' => $counts->all(),
                'borderColor' => '#283d30',
                'backgroundColor' => 'rgba(40,61,48,.12)',
                'pointRadius' => 3,
                'pointHoverRadius' => 4,
                'tension' => 0,
            ]],
            'labels' => $labels->map(fn (Carbon $month) => $month->translatedFormat('M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        $maximumCount = max($this->getCachedData()['datasets'][0]['data'] ?: [0]);
        $stepSize = $maximumCount <= 5 ? 1 : 5;
        $axisMaximum = $maximumCount <= 5
            ? 5
            : (int) (ceil($maximumCount / 5) * 5);

        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'min' => 0,
                    'max' => $axisMaximum,
                    'ticks' => ['stepSize' => $stepSize],
                ],
            ],
        ];
    }
}
