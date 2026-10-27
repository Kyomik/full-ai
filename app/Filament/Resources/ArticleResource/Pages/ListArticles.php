<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;
    protected static string $view = 'filament.resources.articles.pages.list';

    public string $statusFilter = '';
    public string $publishedFrom = '';
    public string $publishedUntil = '';

    public function getTitle(): string
    {
        return 'Data Berita';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data berita dan informasi terbaru pada sistem.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.articles.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Berita',
        ];
    }

    public function resetArticleFilters(): void
    {
        $this->tableSearch = '';
        $this->statusFilter = '';
        $this->publishedFrom = '';
        $this->publishedUntil = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->deferLoading()->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->statusFilter === 'active', fn (Builder $q) => $q->whereNotNull('published_at')->where('published_at', '<=', now()))
                ->when($this->statusFilter === 'inactive', fn (Builder $q) => $q->where(fn (Builder $sub) => $sub->whereNull('published_at')->orWhere('published_at', '>', now())))
                ->when($this->publishedFrom !== '', fn (Builder $q) => $q->whereDate('published_at', '>=', $this->publishedFrom))
                ->when($this->publishedUntil !== '', fn (Builder $q) => $q->whereDate('published_at', '<=', $this->publishedUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Berita')->url(ArticleResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (\App\Models\Article $record) => route('article.show', $record))
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            EditAction::make()
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            DeleteAction::make()
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
        ];
    }
}
