<?php

namespace App\Filament\Resources\AgencyResource\Pages;

use App\Filament\Resources\AgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgency extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (\App\Models\Agency $record): bool => $record->events()->exists())
                ->tooltip(fn (\App\Models\Agency $record): ?string => $record->events()->exists()
                    ? 'Instansi masih memiliki festival atau event. Nonaktifkan instansi jika tidak ingin digunakan lagi.'
                    : null),
        ];
    }
}
