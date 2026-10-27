<?php

namespace App\Filament\Pages;

use App\Models\AgencyType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\View\View;

class AgencyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profil Penyelenggara';
    protected static ?string $title = 'Profil Penyelenggara';
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.agency-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $agency = auth()->user()->agency;

        $this->form->fill([
            ...($agency?->only(['name', 'type', 'email', 'phone', 'address', 'logo']) ?? []),
            'contact_name' => auth()->user()->name,
            'contact_email' => auth()->user()->email,
            'contact_username' => auth()->user()->username,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('profileTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Data Penyelenggara')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Forms\Components\Section::make('Informasi Penyelenggara')
                                    ->icon('heroicon-o-building-office-2')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->label('Nama Penyelenggara')->required(),
                                        Forms\Components\Select::make('type')
                                            ->label('Jenis Penyelenggara')
                                            ->options(fn () => AgencyType::query()->where('status', 'active')->orderBy('name')->pluck('name', 'name'))
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nama jenis instansi')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->unique('agency_types', 'name'),
                                            ])
                                            ->createOptionUsing(fn (array $data): string => AgencyType::create([
                                                'name' => $data['name'],
                                                'status' => 'active',
                                            ])->name)
                                            ->required(),
                                        Forms\Components\Textarea::make('address')->label('Alamat Instansi')->required()->rows(2),
                                        Forms\Components\Textarea::make('description')->label('Deskripsi Penyelenggara')->rows(2)->disabled()->dehydrated(false)->placeholder('Deskripsi penyelenggara belum tersedia.'),
                                        Forms\Components\TextInput::make('email')->label('Email Resmi Penyelenggara')->email()->required(),
                                        Forms\Components\TextInput::make('phone')->label('Telepon / Fax'),
                                        Forms\Components\TextInput::make('website')->label('Website')->disabled()->dehydrated(false)->placeholder('Belum tersedia'),
                                        Forms\Components\TextInput::make('founded_year')->label('Tahun Berdiri / Dibentuk')->disabled()->dehydrated(false)->placeholder('Belum tersedia'),
                                        Forms\Components\FileUpload::make('logo')->label('Logo Penyelenggara')->image()->disk('public')->directory('agencies/logos')->imageEditor()->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Kontak Penanggung Jawab')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Section::make('Informasi Kontak Penanggung Jawab')
                                    ->icon('heroicon-o-user')
                                    ->schema([
                                        Forms\Components\TextInput::make('contact_name')->label('Nama Penanggung Jawab')->disabled()->dehydrated(false),
                                        Forms\Components\TextInput::make('contact_username')->label('Username')->disabled()->dehydrated(false),
                                        Forms\Components\TextInput::make('contact_email')->label('Email Penanggung Jawab')->email()->disabled()->dehydrated(false),
                                        Forms\Components\Placeholder::make('contact_note')->label('Pengaturan kontak')->content('Nama, username, email, dan password akun dapat diperbarui melalui halaman Pengaturan Akun.'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        auth()->user()->agency?->update(array_intersect_key($state, array_flip(['name', 'type', 'email', 'phone', 'address', 'logo'])));
        Notification::make()->title('Profil instansi disimpan')->success()->send();
    }

    public function getSubheading(): ?string
    {
        return 'Kelola informasi dan data penyelenggara Anda. Pastikan data yang diisi sudah benar dan terbaru.';
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.agency-profile-header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'Dashboard',
            'Profil Penyelenggara',
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'agency_admin' && auth()->user()?->agency !== null;
    }
}
