<?php

namespace App\Filament\Widgets;

use App\Models\Category;

class CategoryBreakdown extends DonutBreakdown
{
    protected static ?string $heading = 'Event Berdasarkan Kategori';
    protected int|string|array $columnSpan = ['default' => 1, 'md' => 4];

    public static function canView(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    protected function getBreakdown(): array
    {
        return Category::query()
            ->withCount('events')
            ->orderByDesc('events_count')
            ->pluck('events_count', 'name')
            ->map(fn ($count): int => (int) $count)
            ->all();
    }
}
