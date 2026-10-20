<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 1;

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 6,
        ];
    }

    public function getHeading(): string
    {
        return 'Dashboard';
    }

    public function getSubheading(): ?string
    {
        return 'Selamat datang, ' . (auth()->user()?->name ?? 'Admin') . '!';
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }
}
