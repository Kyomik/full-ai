<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListArticles extends ListRecords
    protected static bool $lazy = true;
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
        return parent::table($table)->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->statusFilter === 'active', fn (Builder $query): Builder => $query->whereNotNull('published_at')->where('published_at', '<=', now()))
                ->when($this->statusFilter === 'inactive', fn (Builder $query): Builder => $query->where(fn (Builder $query): Builder => $query->whereNull('published_at')->orWhere('published_at', '>', now())))
                ->when($this->publishedFrom !== '', fn (Builder $query): Builder => $query->whereDate('published_at', '>=', $this->publishedFrom))
                ->when($this->publishedUntil !== '', fn (Builder $query): Builder => $query->whereDate('published_at', '<=', $this->publishedUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Berita'),
        ];
    }
}
