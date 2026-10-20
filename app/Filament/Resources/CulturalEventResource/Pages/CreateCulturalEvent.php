<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCulturalEvent extends CreateRecord
{
    protected static string $resource = CulturalEventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['agency_id'] = auth()->user()->role === 'super_admin' ? $data['agency_id'] : auth()->user()->agency_id;
        $data['slug'] = \Illuminate\Support\Str::slug($data['title']).'-'.\Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6));
        $data['status'] = 'pending';
        return $data;
    }
}
