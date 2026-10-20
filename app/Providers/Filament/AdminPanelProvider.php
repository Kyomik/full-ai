<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\AgencyLogin;
use App\Providers\Filament\Concerns\BuildsAdminPanel;
use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    use BuildsAdminPanel;

    public function panel(Panel $panel): Panel
    {
        return $this->configureAdminPanel(
            panel: $panel->default(),
            id: 'admin',
            path: 'admin',
            loginPage: AgencyLogin::class,
        );
    }
}
