<?php

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use App\Models\AgencyType;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListAgencies extends ListRecords
{
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
        return AgencyType::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'name')
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
        return parent::table($table)->deferLoading()->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->typeFilter !== '', fn (Builder $q) => $q->where('type', $this->typeFilter))
                ->when($this->statusFilter !== '', fn (Builder $q) => $q->where('status', $this->statusFilter));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Instansi')->url(AgencyResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            DeleteAction::make()
                ->disabled(fn (\App\Models\Agency $record): bool => $record->events()->exists())
                ->tooltip(fn (\App\Models\Agency $record): ?string => $record->events()->exists()
                    ? 'Instansi masih memiliki festival atau event. Nonaktifkan instansi jika tidak ingin digunakan lagi.'
                    : null)
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
        ];
    }
}
