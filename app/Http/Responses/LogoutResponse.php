<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Handle the logout response.
     */
    public function toResponse($request): RedirectResponse
    {
        session()->flash('logout_success', [
            'title' => 'Berhasil logout',
            'body' => 'Anda telah keluar dari sistem.',
        ]);

        return redirect()->route('home');
    }
}
