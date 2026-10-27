<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use App\Models\User;
use App\Notifications\EventSubmittedForReview;
use Filament\Actions;
use App\Filament\Resources\Pages\CreateRecord;

class CreateCulturalEvent extends CreateRecord
{
    protected static string $resource = CulturalEventResource::class;

    public function getTitle(): string
    {
        return auth()->user()?->role === 'super_admin' ? 'Tambah Festival & Event' : 'Pengajuan Festival & Event';
    }

    public function getSubheading(): ?string
    {
        return auth()->user()?->role === 'super_admin'
            ? 'Buat festival atau event budaya baru.'
            : 'Lengkapi data festival atau event yang akan diajukan. Pastikan semua informasi diisi dengan benar.';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['agency_id'] = auth()->user()->role === 'super_admin' ? $data['agency_id'] : auth()->user()->agency_id;
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']).'-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
        $data['status'] = 'pending';
        return $data;
    }

    protected function afterCreate(): void
    {
        if (auth()->user()?->role !== 'agency_admin') {
            return;
        }

        $event = $this->record->load('agency');

        User::query()
            ->where('role', 'super_admin')
            ->where('status', 'active')
            ->get()
            ->each(fn (User $superAdmin) => $superAdmin->notify(new EventSubmittedForReview($event)));
    }

    public function createAnother(): void
    {
        parent::createAnother();
        $this->dispatch('reset-create-wizard');
    }
}
