<?php

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListAgencies extends ListRecords
    protected static bool $lazy = true;
{
    protected static bool $lazy = true;

    protected static string $resource = AgencyResource::class;

    protected static string $view = 'filament.resources.agencies.pages.list';

    public string $typeFilter = '';
    public string $statusFilter = '';

    public function getTitle(): string
    {
        return 'Data Instansi';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola data instansi yang terdaftar pada sistem.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.agencies.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Instansi',
        ];
    }

    public function getAgencyTypes(): array
    {
        return \App\Models\Agency::query()
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type', 'type')
            ->all();
    }

    public function resetAgencyFilters(): void
    {
        $this->tableSearch = '';
        $this->typeFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(function (Builder $query): Builder {
                return $query
                    ->when($this->typeFilter !== '', fn (Builder $query) => $query->where('type', $this->typeFilter))
                    ->when($this->statusFilter !== '', fn (Builder $query) => $query->where('status', $this->statusFilter));
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Instansi'),
        ];
    }
}
