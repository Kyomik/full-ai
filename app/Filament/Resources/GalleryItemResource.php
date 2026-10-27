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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                Forms\Components\Select::make('cultural_event_id')->label('Acara')->relationship('event', 'title', modifyQueryUsing: fn (Builder $query) => $query->where('status', 'approved'))->searchable()->preload(),
                Forms\Components\FileUpload::make('file_path')->label('Foto / video')->disk('public')->directory('gallery')->acceptedFileTypes([
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/gif',
                    'video/mp4',
                    'video/webm',
                    'video/quicktime',
                ])->required(),
                Forms\Components\Toggle::make('is_published')->label('Tampilkan di galeri')->default(true),
            ]);
    }

    public static function detectMediaType(?string $path): string
    {
        if (! $path) {
            return 'photo';
        }

        try {
            $mimeType = Storage::disk('public')->mimeType($path);

            if (is_string($mimeType) && Str::startsWith($mimeType, 'video/')) {
                return 'video';
            }
        } catch (\Throwable) {
            // Fall back to the file extension when the file is not available yet.
        }

        return in_array(Str::lower(pathinfo($path, PATHINFO_EXTENSION)), ['mp4', 'webm', 'mov', 'm4v'], true)
            ? 'video'
            : 'photo';
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
            ->actionsAlignment('start')
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
