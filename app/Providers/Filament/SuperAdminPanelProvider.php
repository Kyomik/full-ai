<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Providers\Filament\Concerns\BuildsAdminPanel;
use Filament\Panel;
use Filament\PanelProvider;

class SuperAdminPanelProvider extends PanelProvider
{
    use BuildsAdminPanel;

    public function panel(Panel $panel): Panel
    {
        return $this->configureAdminPanel(
            panel: $panel,
            id: 'super',
            path: 'super',
            loginPage: Login::class,
        );
    }
}
