<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Username')
            ->placeholder('Masukkan username')
            ->prefixIcon('heroicon-o-user')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $identifier = trim($data['email']);
        $credentialField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $credentialField => $identifier,
            'password' => $data['password'],
        ];
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()->hint(null)->label('Password')->placeholder('Masukkan password')->prefixIcon('heroicon-o-lock-closed');
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        Grid::make(2)->schema([
                            $this->getRememberFormComponent()
                                ->label('Ingat saya')
                                ->extraFieldWrapperAttributes(['class' => 'login-remember']),
                            Placeholder::make('forgot_password')
                                ->hiddenLabel()
                                ->content(new HtmlString('<div class="text-right"><a class="login-forgot" href="'.e(filament()->getRequestPasswordResetUrl()).'">Lupa password?</a></div>')),
                        ])->columnSpanFull(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
