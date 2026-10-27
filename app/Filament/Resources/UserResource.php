<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 13; }
    public static function getNavigationLabel(): string { return 'Pengguna Sistem'; }
    protected static ?string $modelLabel = 'Pengelola';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Nama')->required(),
                Forms\Components\TextInput::make('username')->label('Username')->alphaDash()->maxLength(50)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord:true),
                Forms\Components\Select::make('agency_id')->label('Instansi')->relationship('agency', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('status', 'active'))->searchable()->preload()->required(fn(Forms\Get $get)=>$get('role')!=='super_admin')->visible(fn(Forms\Get $get)=>$get('role')!=='super_admin'),
                Forms\Components\Select::make('role')->label('Peran')->options(['agency_admin'=>'Admin instansi','super_admin'=>'Super admin'])->required(),
                Forms\Components\Select::make('status')->label('Status')->options(['active'=>'Aktif','inactive'=>'Nonaktif'])->required(),
                Forms\Components\TextInput::make('password')->label('Kata sandi baru')->password()->revealable()->minLength(10)->dehydrated(fn($state)=>filled($state))->required(fn(string $operation)=>$operation==='create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('username')->label('Username')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('agency.name')->label('Instansi'),
                Tables\Columns\TextColumn::make('role')->label('Peran')->badge()
                    ->formatStateUsing(fn ($state): string => $state === 'super_admin' ? 'Super Admin' : 'Admin Instansi')
                    ->color(fn (string $state): string => $state === 'super_admin' ? 'warning' : 'info'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'active' ? 'Aktif' : 'Nonaktif')
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('start')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->color('warning')
                    ->extraAttributes(['class' => 'user-edit-action']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([6, 12, 24])
            ->defaultPaginationPageOption(6);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
