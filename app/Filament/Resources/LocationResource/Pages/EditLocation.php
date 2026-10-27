<?php

namespace App\Filament\Resources\LocationResource\Pages;

use App\Filament\Resources\LocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocation extends EditRecord
{
    protected static string $resource = LocationResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (\App\Models\Location $record): bool => $record->events()->exists())
                ->tooltip(fn (\App\Models\Location $record): ?string => $record->events()->exists()
                    ? 'Lokasi masih dipakai oleh festival atau event. Nonaktifkan lokasi jika tidak ingin digunakan lagi.'
                    : null),
        ];
    }
}
