<?php

namespace App\Filament\Resources\GalleryItemResource\Pages;

use App\Filament\Resources\GalleryItemResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
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
        return Category::query()->orderBy('name')->pluck('name', 'id')->all();
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
        return parent::table($table)->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->categoryFilter !== '', fn (Builder $query): Builder => $query->whereHas('event', fn (Builder $eventQuery): Builder => $eventQuery->where('category_id', $this->categoryFilter)))
                ->when($this->typeFilter !== '', fn (Builder $query): Builder => $query->where('type', $this->typeFilter))
                ->when($this->uploadedFrom !== '', fn (Builder $query): Builder => $query->whereDate('created_at', '>=', $this->uploadedFrom))
                ->when($this->uploadedUntil !== '', fn (Builder $query): Builder => $query->whereDate('created_at', '<=', $this->uploadedUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Galeri'),
        ];
    }
}
