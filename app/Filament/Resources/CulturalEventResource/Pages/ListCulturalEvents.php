<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListCulturalEvents extends ListRecords
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
        return auth()->user()?->role === 'super_admin' ? 'Data Festival & Event' : 'Daftar Pengajuan';
    }

    public function getSubheading(): ?string
    {
        return auth()->user()?->role === 'super_admin'
            ? 'Kelola data festival dan event yang telah disetujui.'
            : 'Berikut adalah daftar pengajuan festival & event yang telah Anda ajukan.';
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
        return \App\Models\Category::query()->where('status', 'active')->orderBy('name')->pluck('name', 'id')->all();
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
        return parent::table($table)->deferLoading()->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->categoryFilter !== '', fn (Builder $q) => $q->where('category_id', $this->categoryFilter))
                ->when($this->agencyFilter !== '' && auth()->user()?->role === 'super_admin', fn (Builder $q) => $q->where('agency_id', $this->agencyFilter))
                ->when($this->statusFilter !== '', fn (Builder $q) => $q->where('status', $this->statusFilter))
                ->when($this->dateFrom !== '', fn (Builder $q) => $q->whereDate('starts_on', '>=', $this->dateFrom))
                ->when($this->dateUntil !== '', fn (Builder $q) => $q->whereDate('ends_on', '<=', $this->dateUntil));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Festival / Event')->url(CulturalEventResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (\App\Models\CulturalEvent $record) => route('events.show', $record))
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            Action::make('approve')
                ->label('Setuju')
                ->icon('heroicon-o-check')
                ->action(fn (\App\Models\CulturalEvent $record) => $record->update(['status' => 'approved']))
                ->requiresConfirmation()
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x')
                ->action(fn (\App\Models\CulturalEvent $record) => $record->update(['status' => 'rejected']))
                ->requiresConfirmation()
                ->extraAttributes([
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
            EditAction::make()
                ->url(fn (\App\Models\CulturalEvent $record): string => CulturalEventResource::getUrl('edit', [
                    'record' => $record,
                    'from' => auth()->user()?->role === 'agency_admin' ? 'submissions' : 'events',
                ]))
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
