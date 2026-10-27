<?php

namespace App\Filament\Resources\GalleryItemResource\Pages;

use App\Filament\Resources\GalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGalleryItem extends EditRecord
{
    protected static string $resource = GalleryItemResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['file_path'])) {
            $data['type'] = GalleryItemResource::detectMediaType($data['file_path']);
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
