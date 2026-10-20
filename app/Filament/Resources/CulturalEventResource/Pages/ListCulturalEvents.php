<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListCulturalEvents extends ListRecords
    protected static bool $lazy = true;
{
    protected static string $resource = CulturalEventResource::class;
    protected static string $view = 'filament.resources.cultural-events.pages.list';

    public string $categoryFilter = '';
    public string $agencyFilter = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateUntil = '';

    public function getTitle(): string
    {
        return auth()->user()?->role === 'super_admin' ? 'Data Festival & Event' : 'Pengajuan Festival & Event';
    }

    public function getSubheading(): ?string
    {
        return auth()->user()?->role === 'super_admin'
            ? 'Kelola data festival dan event yang telah disetujui.'
            : 'Kelola pengajuan festival dan event dari instansi Anda.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.cultural-events.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Festival & Event',
        ];
    }

    public function getCategoryOptions(): array
    {
        return \App\Models\Category::query()->orderBy('name')->pluck('name', 'id')->all();
    }

    public function getAgencyOptions(): array
    {
        return \App\Models\Agency::query()->where('status', 'active')->orderBy('name')->pluck('name', 'id')->all();
    }

    public function resetEventFilters(): void
    {
        $this->tableSearch = '';
        $this->categoryFilter = '';
        $this->agencyFilter = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateUntil = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->categoryFilter !== '', fn (Builder $query) => $query->where('category_id', $this->categoryFilter))
                ->when($this->agencyFilter !== '' && auth()->user()?->role === 'super_admin', fn (Builder $query) => $query->where('agency_id', $this->agencyFilter))
                ->when($this->statusFilter !== '', fn (Builder $query) => $query->where('status', $this->statusFilter))
                ->when($this->dateFrom !== '', fn (Builder $query) => $query->whereDate('starts_on', '>=', $this->dateFrom))
                ->when($this->dateUntil !== '', fn (Builder $query) => $query->whereDate('ends_on', '<=', $this->dateUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Festival / Event'),
        ];
    }
}
