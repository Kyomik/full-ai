<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $modelLabel = 'Berita';

    public static function getNavigationGroup(): ?string { return null; }
    public static function getNavigationSort(): ?int { return 6; }
    public static function getNavigationLabel(): string { return 'Berita'; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('author');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Judul')->required()->maxLength(200),
                Forms\Components\Textarea::make('excerpt')->label('Ringkasan')->rows(2),
                Forms\Components\RichEditor::make('body')->label('Isi berita')->required()->columnSpanFull(),
                Forms\Components\FileUpload::make('image')->label('Gambar utama')->image()->disk('public')->directory('news'),
                Forms\Components\DateTimePicker::make('published_at')->label('Terbit pada'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
                Tables\Columns\ImageColumn::make('image')->label('Gambar')->disk('public')->square()->size(40),
                Tables\Columns\TextColumn::make('title')->label('Judul Berita')->searchable()->wrap()
                    ->description(fn (Article $record): ?string => $record->excerpt ? \Illuminate\Support\Str::limit(strip_tags($record->excerpt), 110) : null),
                Tables\Columns\TextColumn::make('published_at')->label('Tanggal Publikasi')->dateTime('d M Y H:i')->sortable()->placeholder('-'),
                Tables\Columns\TextColumn::make('author.name')->label('Penulis')->placeholder('-'),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                    ->getStateUsing(fn (Article $record): string => $record->published_at && $record->published_at->isPast() ? 'Aktif' : 'Nonaktif')
                    ->color(fn (string $state): string => $state === 'Aktif' ? 'success' : 'gray'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool { return auth()->user()?->role === 'super_admin'; }
}
