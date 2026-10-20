<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Logout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-left-on-rectangle';
    protected static ?string $navigationLabel = 'Logout';
    protected static ?string $title = 'Keluar dari sistem';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.logout';

    public static function getNavigationGroup(): ?string { return null; }
    public static function canAccess(): bool { return auth()->check(); }

    public function logout(): void
    {
        Auth::guard(filament()->getAuthGuard())->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        $this->redirect(route('home'));
    }
}
