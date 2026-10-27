<?php

namespace App\Providers;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom logout response
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );

        // Bind custom DatabaseNotifications
        $this->app->bind(
            \Filament\Livewire\DatabaseNotifications::class,
            \App\Livewire\DatabaseNotifications::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        EditAction::configureUsing(fn (EditAction $action): EditAction => $action->color('warning'));
        ViewAction::configureUsing(fn (ViewAction $action): ViewAction => $action->color('gray'));

        // Replace both Filament's panel alias and the notification package alias.
        $this->app->booted(function (): void {
            \Livewire\Livewire::component('filament.livewire.database-notifications', \App\Livewire\DatabaseNotifications::class);
            \Livewire\Livewire::component('database-notifications', \App\Livewire\DatabaseNotifications::class);
        });

        // Bind repository interfaces to implementations
        $this->app->bind(
            \App\Repositories\Contracts\PublicAnnouncementRepositoryInterface::class,
            \App\Repositories\Eloquent\PublicAnnouncementRepository::class
        );

        //
    }

}
