<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
    protected static bool $lazy = true;
{
    protected static string $resource = CategoryResource::class;
    protected static string $view = 'filament.resources.categories.pages.list';

    public string $statusFilter = '';

    public function getTitle(): string
    {
        return 'Data Kategori';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data kategori festival dan event budaya.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.categories.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Kategori',
        ];
    }

    public function resetCategoryFilters(): void
    {
        $this->tableSearch = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->modifyQueryUsing(fn (Builder $query): Builder => $query
            ->when($this->statusFilter !== '', fn (Builder $query): Builder => $query->where('status', $this->statusFilter)));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Kategori'),
        ];
    }
}
