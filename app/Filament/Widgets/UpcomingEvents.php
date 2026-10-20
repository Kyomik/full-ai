<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingEvents extends TableWidget
{
    protected static ?string $heading = 'Event Mendatang';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 2,
    ];

    protected function getTableQuery(): Builder
    {
        return CulturalEvent::query()
            ->with('agency')
            ->where('status', 'approved')
            ->whereDate('starts_on', '>=', today())
            ->orderBy('starts_on');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('starts_on')->label('Tanggal')->date('d M')->weight('bold'),
            Tables\Columns\TextColumn::make('title')->label('Event')->limit(25),
            Tables\Columns\TextColumn::make('venue')->label('Lokasi')->limit(22),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [3];
    }

    protected function getDefaultTableRecordsPerPage(): int
    {
        return 3;
    }
}
