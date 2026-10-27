<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use App\Models\Location;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListLocations extends ListRecords
{

    protected static string $resource = LocationResource::class;
    protected static string $view = 'filament.resources.locations.pages.list';

    public string $categoryFilter = '';
    public string $statusFilter = '';

    public function getTitle(): string
    {
        return 'Data Lokasi';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data lokasi penyelenggaraan festival & event budaya.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.locations.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Lokasi',
        ];
    }

    public function getCategoryOptions(): array
    {
        return Location::CATEGORIES;
    }

    public function resetLocationFilters(): void
    {
        $this->tableSearch = '';
        $this->categoryFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->deferLoading()->modifyQueryUsing(fn (Builder $query): Builder => $query
            ->when($this->categoryFilter !== '', fn (Builder $query): Builder => $query->where('type', $this->categoryFilter))
            ->when($this->statusFilter !== '', fn (Builder $query): Builder => $query->where('status', $this->statusFilter)));
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()->label('Tambah Lokasi')->url(LocationResource::getUrl('create'))];
    }
}
