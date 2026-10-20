<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use App\Filament\Resources\CulturalEventResource;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentSubmissions extends TableWidget
{
    protected static ?string $heading = 'Pengajuan terbaru';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = [
        'default' => 1,
        'md' => 3,
    ];

    protected function getTableQuery(): Builder
    {
        $query = CulturalEvent::query()->with(['agency', 'category'])->latest();
        if (auth()->user()?->role === 'agency_admin') {
            $query->where('agency_id', auth()->user()->agency_id);
        }

        return $query;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
            Tables\Columns\TextColumn::make('title')->label('Judul Event')->limit(28),
            Tables\Columns\TextColumn::make('agency.name')->label('Instansi')->visible(fn () => auth()->user()?->role === 'super_admin'),
            Tables\Columns\TextColumn::make('starts_on')->label('Tanggal')->date('d M Y'),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'approved' => 'Disetujui', 'rejected' => 'Ditolak', default => 'Menunggu',
                })
                ->color(fn (string $state): string => match ($state) {
                    'approved' => 'success', 'rejected' => 'danger', default => 'warning',
                }),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\Action::make('lihat_semua')
                ->label('Lihat Semua')
                ->url(fn (): string => CulturalEventResource::getUrl('index'))
                ->link()
                ->color('gray'),
        ];
    }

    protected function getTableDefaultSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getTableDefaultSortDirection(): ?string
    {
        return 'desc';
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
