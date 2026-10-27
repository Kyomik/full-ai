<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.users.pages.list';

    public string $roleFilter = '';
    public string $statusFilter = '';

    public function getTitle(): string
    {
        return 'Pengguna Sistem';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola akun Super Admin dan Admin Instansi yang memiliki akses ke sistem.';
    }

    public function getHeader(): ?View
    {
        return view('filament.resources.users.pages.header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            \App\Filament\Pages\Dashboard::getUrl() => 'Dashboard',
            'Pengguna Sistem',
        ];
    }

    public function resetUserFilters(): void
    {
        $this->tableSearch = '';
        $this->roleFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return parent::table($table)->deferLoading()->modifyQueryUsing(function (Builder $query): Builder {
            return $query
                ->when($this->roleFilter !== '', fn (Builder $q) => $q->where('role', $this->roleFilter))
                ->when($this->statusFilter !== '', fn (Builder $q) => $q->where('status', $this->statusFilter));
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pengguna')->url(UserResource::getUrl('create')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->label('Ubah')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'user-edit-action',
                    'wire:loading.attr' => 'disabled',
                    'wire:loading.class' => 'opacity-50',
                ]),
        ];
    }
}
