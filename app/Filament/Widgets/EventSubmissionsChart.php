<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class EventSubmissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Event per Bulan';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 3,
    ];

    protected function getData(): array
    {
        $user = auth()->user();
        $query = CulturalEvent::query();
        if ($user?->role === 'agency_admin') {
            $query->where('agency_id', $user->agency_id);
        }

        $labels = collect(range(11, 0))->map(fn (int $offset) => now()->subMonths($offset)->startOfMonth());
        $counts = $labels->map(fn (Carbon $month) => (clone $query)
            ->whereBetween('created_at', [$month, $month->copy()->endOfMonth()])->count());

        return [
            'datasets' => [['label' => 'Pengajuan', 'data' => $counts->all(), 'borderColor' => '#283d30', 'backgroundColor' => 'rgba(40,61,48,.12)']],
            'labels' => $labels->map(fn (Carbon $month) => $month->translatedFormat('M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
