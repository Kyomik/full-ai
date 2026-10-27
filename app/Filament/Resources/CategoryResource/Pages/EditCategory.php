<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (\App\Models\Category $record): bool => $record->events()->exists())
                ->tooltip(fn (\App\Models\Category $record): ?string => $record->events()->exists()
                    ? 'Kategori masih dipakai oleh festival atau event. Nonaktifkan kategori jika tidak ingin digunakan lagi.'
                    : null),
        ];
    }
}
