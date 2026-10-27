<?php

namespace App\Filament\Resources\GalleryItemResource\Pages;

use App\Filament\Resources\GalleryItemResource;
use App\Models\Category;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListGalleryItems extends ListRecords
{
    protected static string $resource = GalleryItemResource::class;
    protected static string $view = 'filament.resources.gallery.pages.list';

    public string $categoryFilter = '';
    public string $typeFilter = '';
    public string $uploadedFrom = '';
    public string $uploadedUntil = '';

    public function getTitle(): string
    {
        return 'Data Galeri';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data galeri foto dan video festival & event budaya.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.gallery.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Galeri',
        ];
    }

    public function getCategoryOptions(): array
    {
        return Category::query()->where('status', 'active')->orderBy('name')->pluck('name', 'id')->all();
    }

    public function resetGalleryFilters(): void
    {
        $this->tableSearch = '';
        $this->categoryFilter = '';
        $this->typeFilter = '';
        $this->uploadedFrom = '';
        $this->uploadedUntil = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->deferLoading()->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->categoryFilter !== '', fn (Builder $q) => $q->whereHas('event', fn (Builder $event) => $event->where('category_id', $this->categoryFilter)))
                ->when($this->typeFilter !== '', fn (Builder $q) => $q->where('type', $this->typeFilter))
                ->when($this->uploadedFrom !== '', fn (Builder $q) => $q->whereDate('created_at', '>=', $this->uploadedFrom))
                ->when($this->uploadedUntil !== '', fn (Builder $q) => $q->whereDate('created_at', '<=', $this->uploadedUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Galeri')->url(GalleryItemResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (\App\Models\GalleryItem $record) => route('gallery.show', $record))
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
