<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class CategoryBreakdown extends ChartWidget
{
    protected static ?string $heading = 'Event Berdasarkan Kategori';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
    ];

    protected function getData(): array
    {
        $user = auth()->user();
        $query = Category::query()->withCount(['events' => function ($query) use ($user) {
            if ($user?->role === 'agency_admin') {
                $query->where('agency_id', $user->agency_id);
            }
        }])->orderByDesc('events_count');

        $categories = $query->get();

        return [
            'labels' => $categories->pluck('name')->all(),
            'datasets' => [[
                'data' => $categories->pluck('events_count')->all(),
                'backgroundColor' => ['#283d30', '#718096', '#a0aec0', '#cbd5e0', '#4a5568', '#d1d5db'],
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
