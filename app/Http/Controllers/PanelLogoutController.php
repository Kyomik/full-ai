<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationReadBatch;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Illuminate\Http\Request;

class PanelLogoutController
{
    public function __invoke(Request $request): LogoutResponse
    {
        $user = Filament::auth()->user();

        if ($user instanceof User) {
            app(NotificationReadBatch::class)->flush($user);
        }

        Filament::auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
