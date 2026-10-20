<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;

class AgencyLogin extends Login
{
    protected static string $view = 'filament.pages.auth.agency-login';

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email / Username')
            ->placeholder('Masukkan email atau username')
            ->prefixIcon('heroicon-o-user')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
}
