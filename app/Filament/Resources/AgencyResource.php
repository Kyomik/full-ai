<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Models\Agency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $modelLabel = 'Instansi';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 4; }
    public static function getNavigationLabel(): string { return 'Instansi'; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama instansi')->required(),
                Forms\Components\TextInput::make('type')->label('Jenis')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->label('Telepon'),
                Forms\Components\FileUpload::make('logo')->label('Logo instansi')->image()->disk('public')->directory('agencies/logos'),
                Forms\Components\Textarea::make('address')->label('Alamat')->columnSpanFull(),
                Forms\Components\Select::make('status')->options(['active'=>'Aktif','inactive'=>'Nonaktif'])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\ImageColumn::make('logo')->label('Logo')->disk('public')->square()->size(38),
                Tables\Columns\TextColumn::make('name')->label('Nama Instansi')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('type')->label('Jenis Instansi')->sortable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('No. Telepon')->placeholder('-'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Aktif' : 'Nonaktif')
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('end')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat')->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->paginated([6, 12, 24])
            ->defaultPaginationPageOption(6)
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
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
