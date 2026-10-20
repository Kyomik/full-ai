<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(
            \App\Repositories\Contracts\AnnouncementRepositoryInterface::class,
            \App\Repositories\Eloquent\AnnouncementRepository::class
        );

        //
    }
}
