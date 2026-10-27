<?php

namespace App\Providers\Filament\Concerns;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

trait BuildsAdminPanel
{
    protected function configureAdminPanel(Panel $panel, string $id, string $path, string $loginPage): Panel
    {
        return $panel
            ->id($id)
            ->path($path)
            ->login($loginPage)
            ->passwordReset()
            ->brandName('Sistem Informasi Festival & Event Budaya Kota Palopo')
            ->simplePageMaxContentWidth('5xl')
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->databaseNotifications(isLazy: true)
            // The notification bell is present on every panel page. Poll less
            // often so it does not contend with SPA navigation requests.
            ->databaseNotificationsPolling('60s')
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn () => view('filament.components.admin-topbar-brand'),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn () => view('filament.components.sidebar-identity'),
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn () => view('filament.components.breadcrumb-styles'),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn () => view('filament.components.sidebar-logout'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn () => view('filament.components.logout-modal'),
            )
            ->sidebarWidth('16rem')
            ->navigationGroups([])
            ->colors(['primary' => Color::Green])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\PortalOverview::class,
                \App\Filament\Widgets\EventSubmissionsChart::class,
                \App\Filament\Widgets\RecentSubmissions::class,
                \App\Filament\Widgets\CategoryBreakdown::class,
                \App\Filament\Widgets\SubmissionStatusBreakdown::class,
                \App\Filament\Widgets\UpcomingEvents::class,
                \App\Filament\Widgets\RecentAnnouncements::class,
                \App\Filament\Widgets\RecentActivity::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
