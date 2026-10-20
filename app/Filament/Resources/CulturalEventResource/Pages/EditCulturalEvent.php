<?php

namespace App\Filament\Resources\CulturalEventResource\Pages;

use App\Filament\Resources\CulturalEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulturalEvent extends EditRecord
{
    protected static string $resource = CulturalEventResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->role === 'agency_admin') {
            $data['status'] = 'pending';
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
            $data['review_note'] = null;
        }
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
