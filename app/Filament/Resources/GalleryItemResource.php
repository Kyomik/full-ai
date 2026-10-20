<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $modelLabel = 'Galeri';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 7; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['event.category', 'uploader']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Judul')->required(),
                Forms\Components\Select::make('cultural_event_id')->label('Acara')->relationship('event','title')->searchable()->preload(),
                Forms\Components\Select::make('type')->label('Jenis')->options(['photo' => 'Foto', 'video' => 'Video'])->default('photo')->required()->live(),
                Forms\Components\FileUpload::make('file_path')->label('Foto / video')->disk('public')->directory('gallery')->acceptedFileTypes(fn (Forms\Get $get): array => $get('type') === 'video' ? ['video/mp4', 'video/webm', 'video/quicktime'] : ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])->required(),
                Forms\Components\Toggle::make('is_published')->label('Tampilkan di galeri')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\ViewColumn::make('thumbnail')->label('Thumbnail')->view('filament.tables.columns.gallery-thumbnail'),
                Tables\Columns\TextColumn::make('title')->label('Judul Galeri')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('event.category.name')->label('Kategori')->placeholder('-')->wrap(),
                Tables\Columns\TextColumn::make('type')->label('Jenis')->formatStateUsing(fn (string $state): string => $state === 'video' ? 'Video' : 'Foto'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Unggah')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('uploader.name')->label('Diunggah Oleh')->placeholder('-'),
                Tables\Columns\TextColumn::make('is_published')->label('Status')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('end')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat')->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->label('Edit'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->paginated([5, 10, 20])
            ->defaultPaginationPageOption(5)
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
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
