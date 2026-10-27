<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Akun';
    protected static ?string $title = 'Pengaturan Akun';
    protected static string $view = 'filament.pages.settings';

    public static function getNavigationSort(): ?int
    {
        return auth()->user()?->role === 'agency_admin' ? 10 : 11;
    }

    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['super_admin', 'agency_admin'], true);
    }

    public function mount(): void
    {
        $this->form->fill([
            'name' => auth()->user()->name,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'password' => '',
            'password_confirmation' => '',
        ]);
    }

    public function getSubheading(): ?string
    {
        return 'Perbarui informasi akun dan kredensial login Anda.';
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.settings-header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'Dashboard',
            'Pengaturan Akun',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(150),
                TextInput::make('username')
                    ->label('Username')
                    ->placeholder('Contoh: nama_pengguna')
                    ->required()
                    ->alphaDash()
                    ->maxLength(50)
                    ->rules([Rule::unique('users', 'username')->ignore(auth()->id())]),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(150)
                    ->rules([Rule::unique('users', 'email')->ignore(auth()->id())]),
                TextInput::make('password')
                    ->label('Password Baru')
                    ->password()
                    ->revealable()
                    ->minLength(10)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Kosongkan jika tidak ingin mengubah password.'),
                TextInput::make('password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->revealable()
                    ->required(fn (\Filament\Forms\Get $get): bool => filled($get('password')))
                    ->same('password')
                    ->dehydrated(false),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $attributes = $this->form->getState();
        unset($attributes['password_confirmation']);

        auth()->user()->update($attributes);

        $user = auth()->user()->refresh();
        $this->form->fill([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        Notification::make()
            ->title('Pengaturan akun berhasil disimpan')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'showAdminLinks' => auth()->user()?->role === 'super_admin',
        ];
    }
}
