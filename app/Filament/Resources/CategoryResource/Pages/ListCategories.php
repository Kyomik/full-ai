<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListCategories extends ListRecords
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
        return parent::table($table)->deferLoading()->modifyQueryUsing(fn (Builder $query) => $query->when($this->statusFilter !== '', fn (Builder $q) => $q->where('status', $this->statusFilter)));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Kategori')->url(CategoryResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (\App\Models\Category $record) => route('categories.show', $record))
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
                ->disabled(fn (\App\Models\Category $record): bool => $record->events()->exists())
                ->tooltip(fn (\App\Models\Category $record): ?string => $record->events()->exists()
                    ? 'Kategori masih dipakai oleh festival atau event. Nonaktifkan kategori jika tidak ingin digunakan lagi.'
                    : null)
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
        ];
    }
}
