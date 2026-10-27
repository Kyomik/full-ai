<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $modelLabel = 'Lokasi';
    protected static ?string $pluralModelLabel = 'Data Lokasi';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 8; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nama lokasi')->required(),
            Forms\Components\Select::make('type')->label('Kategori')->options(Location::CATEGORIES)->required(),
            Forms\Components\Textarea::make('address')->label('Alamat')->required()->columnSpanFull(),
            Forms\Components\TextInput::make('latitude')->numeric(),
            Forms\Components\TextInput::make('longitude')->numeric(),
            Forms\Components\Select::make('status')->options(['active' => 'Aktif', 'inactive' => 'Nonaktif'])->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\TextColumn::make('name')->label('Nama Lokasi')->searchable()->sortable()->wrap(),
                Tables\Columns\TextColumn::make('address')->label('Alamat')->wrap()->limit(100),
                Tables\Columns\TextColumn::make('type')->label('Kategori')->formatStateUsing(fn (string $state): string => Location::CATEGORIES[$state] ?? $state),
                Tables\Columns\TextColumn::make('coordinates')->label('Koordinat')->getStateUsing(fn (Location $record): string => $record->latitude !== null && $record->longitude !== null
                    ? number_format((float) $record->latitude, 6, '.', '') . ', ' . number_format((float) $record->longitude, 6, '.', '')
                    : '-')->wrap(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Aktif' : 'Nonaktif')
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('start')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat')->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->disabled(fn (Location $record): bool => $record->events()->exists())
                    ->tooltip(fn (Location $record): ?string => $record->events()->exists()
                        ? 'Lokasi masih dipakai oleh festival atau event. Nonaktifkan lokasi jika tidak ingin digunakan lagi.'
                        : null),
            ])
            ->paginated([5, 10, 20])
            ->defaultPaginationPageOption(5)
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListLocations::route('/'), 'create' => Pages\CreateLocation::route('/create'), 'edit' => Pages\EditLocation::route('/{record}/edit')];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
