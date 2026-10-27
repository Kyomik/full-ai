<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

abstract class DonutBreakdown extends ChartWidget
{
    protected static ?string $maxHeight = '180px';

    /** @return array<string, int> */
    abstract protected function getBreakdown(): array;

    protected function getData(): array
    {
        $breakdown = $this->getBreakdown();
        $total = array_sum($breakdown);
        $palette = ['#202326', '#555d63', '#777f85', '#92999e', '#adb3b7', '#c3c8cb', '#d6dadd', '#e5e7e9'];

        return [
            'labels' => array_map(
                fn (string $label, int $count): string => $label . '  ' . $count . ' (' . number_format($total ? ($count / $total) * 100 : 0, 1, ',', '.') . '%)',
                array_keys($breakdown),
                array_values($breakdown),
            ),
            'datasets' => [[
                'data' => array_values($breakdown),
                'backgroundColor' => array_slice(array_pad($palette, count($breakdown), '#e5e7e9'), 0, count($breakdown)),
                'borderColor' => '#ffffff',
                'borderWidth' => 2,
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getOptions(): array
    {
        return [
            'cutout' => '68%',
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                    'labels' => [
                        'boxWidth' => 10,
                        'boxHeight' => 10,
                        'padding' => 12,
                        'font' => ['size' => 11],
                    ],
                ],
            ],
        ];
    }

    public function getDescription(): string
    {
        $total = array_sum($this->getBreakdown());

        return 'Total: ' . $total . ' (' . ($total === 0 ? '0' : '100') . '%)';
    }
}
