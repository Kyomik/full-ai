<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class AgencyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Profil Instansi';
    protected static ?string $title = 'Profil Instansi';
    protected static string $view = 'filament.pages.agency-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->agency?->only(['name', 'type', 'email', 'phone', 'address']) ?? []);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nama instansi')->required(),
            Forms\Components\TextInput::make('type')->label('Jenis instansi')->required(),
            Forms\Components\TextInput::make('email')->label('Email resmi')->email()->required(),
            Forms\Components\TextInput::make('phone')->label('Nomor telepon'),
            Forms\Components\Textarea::make('address')->label('Alamat lengkap')->rows(4)->columnSpanFull(),
        ])->columns(2)->statePath('data');
    }

    public function save(): void
    {
        auth()->user()->agency?->update($this->form->getState());
        Notification::make()->title('Profil instansi disimpan')->success()->send();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'agency_admin' && auth()->user()?->agency !== null;
    }
}
