<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CulturalEventResource\Pages;
use App\Filament\Resources\CulturalEventResource\RelationManagers;
use App\Models\CulturalEvent;
use App\Models\User;
use App\Notifications\EventReviewUpdated;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CulturalEventResource extends Resource
{
    protected static ?string $model = CulturalEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $modelLabel = 'Festival & Event';
    protected static ?string $pluralModelLabel = 'Festival & Event';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return auth()->user()?->role === 'super_admin' ? 3 : 1; }

    public static function getNavigationLabel(): string
    {
        return auth()->user()?->role === 'super_admin' ? 'Festival & Event' : 'Daftar Pengajuan';
    }

    public static function getNavigationItems(): array
    {
        if (auth()->user()?->role === 'agency_admin') {
            return [
                \Filament\Navigation\NavigationItem::make('Pengajuan Festival & Event')
                    ->icon('heroicon-o-calendar-days')
                    ->url(static::getUrl('create'))
                    ->sort(2)
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.cultural-events.create')),
                \Filament\Navigation\NavigationItem::make('Daftar Pengajuan')
                    ->icon('heroicon-o-document-text')
                    ->url(static::getUrl('index'))
                    ->sort(3)
                    ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.cultural-events.index') || request()->routeIs('filament.admin.resources.cultural-events.edit')),
            ];
        }

        return [
            \Filament\Navigation\NavigationItem::make(static::getNavigationLabel())
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->activeIcon(static::getActiveNavigationIcon())
                ->isActiveWhen(fn () => request()->routeIs('filament.super.resources.cultural-events.*'))
                ->sort(3)
                ->url(static::getUrl('index')),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['agency','category']);
        return auth()->user()?->role === 'super_admin' ? $query : $query->where('agency_id', auth()->id() ? auth()->user()->agency_id : null);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Informasi umum')->icon('heroicon-o-sparkles')->schema([
                    Forms\Components\Select::make('agency_id')->label('Instansi')->relationship('agency', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('status', 'active'))->searchable()->preload()->required()->visible(fn () => auth()->user()?->role === 'super_admin'),
                    Forms\Components\TextInput::make('title')->label('Nama acara')->required()->maxLength(200),
                    Forms\Components\TextInput::make('theme')->label('Tema')->maxLength(200),
                    Forms\Components\Select::make('category_id')->label('Kategori')->relationship('category', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('status', 'active'))->required(),
                    Forms\Components\Textarea::make('description')->label('Deskripsi acara')->required()->rows(5)->columnSpanFull(),
                    Forms\Components\FileUpload::make('poster')->label('Poster')->image()->disk('public')->directory('events/posters'),
                    ])->columns(2),
                    Forms\Components\Wizard\Step::make('Jadwal kegiatan')->icon('heroicon-o-calendar')->schema([
                    Forms\Components\DatePicker::make('starts_on')->label('Tanggal mulai')->required(),
                    Forms\Components\DatePicker::make('ends_on')->label('Tanggal selesai')->required()->afterOrEqual('starts_on'),
                    Forms\Components\TimePicker::make('starts_at')->label('Waktu mulai'),
                ])->columns(2),
                    Forms\Components\Wizard\Step::make('Lokasi kegiatan')->icon('heroicon-o-map-pin')->schema([
                    Forms\Components\Select::make('location_id')
                        ->label('Lokasi')
                        ->required()
                        ->relationship('location', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('status', 'active'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->helperText('Pilih lokasi dari Data Lokasi.')
                        ->afterStateUpdated(function ($state, Forms\Set $set): void {
                            if (! $state) {
                                return;
                            }

                            $location = \App\Models\Location::query()->find($state);

                            if (! $location) {
                                return;
                            }

                            $set('venue', $location->name);
                            $set('address', $location->address);
                            $set('latitude', $location->latitude);
                            $set('longitude', $location->longitude);
                        })
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('venue'),
                    Forms\Components\Hidden::make('address'),
                    Forms\Components\Hidden::make('latitude'),
                    Forms\Components\Hidden::make('longitude'),
                ])->columns(2),
                    Forms\Components\Wizard\Step::make('Dokumen pendukung')->icon('heroicon-o-paper-clip')->schema([
                    Forms\Components\Repeater::make('documents')->relationship()->label('Dokumen')->schema([
                        Forms\Components\TextInput::make('name')->label('Nama dokumen')->required(),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Berkas')
                            ->disk('public')
                            ->directory('events/documents')
                            ->helperText('Unggah gambar, video, PDF, Word, atau Excel.')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/gif',
                                'video/mp4',
                                'video/webm',
                                'video/quicktime',
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ])
                            ->required(),
                    ])->columns(2)->addActionLabel('Tambah dokumen')->defaultItems(0),
                ])->columns(1),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\ViewColumn::make('event_summary')->label('Judul Event')->view('filament.tables.columns.event-summary')->width('22%'),
                Tables\Columns\TextColumn::make('title')->label('Judul Event')->searchable()->visible(false),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori')->sortable(),
                Tables\Columns\TextColumn::make('agency.name')->label('Instansi')->visible(fn () => auth()->user()?->role === 'super_admin')->wrap(),
                Tables\Columns\TextColumn::make('starts_on')->label('Tanggal Pelaksanaan')->formatStateUsing(fn (CulturalEvent $record): string => $record->starts_on?->format('d M Y') . ($record->ends_on && !$record->starts_on->isSameDay($record->ends_on) ? ' - ' . $record->ends_on->format('d M Y') : ''))->sortable(),
                Tables\Columns\TextColumn::make('venue')->label('Lokasi')->description(fn (CulturalEvent $record): ?string => $record->address)->wrap()->limit(32),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) { 'approved' => 'Dipublikasikan', 'rejected' => 'Ditolak', default => 'Menunggu Verifikasi' })
                    ->color(fn (string $state): string => match ($state) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' }),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('start')
            ->searchable()
            ->actions([
                Tables\Actions\Action::make('view')->label('Lihat')->icon('heroicon-o-eye')->color('gray')->url(fn (CulturalEvent $record): string => static::getUrl('edit', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->visible(fn () => auth()->user()?->role === 'super_admin'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCulturalEvents::route('/'),
            'create' => Pages\CreateCulturalEvent::route('/create'),
            'edit' => Pages\EditCulturalEvent::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool { return in_array(auth()->user()?->role, ['agency_admin', 'super_admin'], true); }
    public static function canDelete($record): bool { return auth()->user()?->role === 'super_admin'; }
}
