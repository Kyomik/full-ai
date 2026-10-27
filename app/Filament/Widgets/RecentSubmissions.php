<?php

namespace App\Filament\Widgets;

use App\Models\CulturalEvent;
use App\Filament\Resources\CulturalEventResource;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class RecentSubmissions extends TableWidget
{
    protected static ?string $heading = 'Pengajuan Terbaru';
    protected static ?int $sort = 4;
    public function getColumnSpan(): int|string|array
    {
        return ['default' => 1, 'md' => 7];
    }

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['agency_admin', 'super_admin'], true);
    }

    protected function getTableQuery(): Builder
    {
        $query = CulturalEvent::query()->with(['agency', 'category'])->latest();
        if (auth()->user()?->role === 'agency_admin') {
            $query->where('agency_id', auth()->user()->agency_id);
        }

        return $query->orderByDesc('created_at')->limit(5);
    }

    protected function getTableHeading(): string | Htmlable | null
    {
        return new HtmlString(view('filament.widgets.recent-submissions-heading')->render());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('row_index')->label('No')->rowIndex(),
            Tables\Columns\TextColumn::make('title')->label('Judul Event')->limit(28),
            Tables\Columns\TextColumn::make('agency.name')->label('Instansi')->visible(fn () => auth()->user()?->role === 'super_admin'),
            Tables\Columns\TextColumn::make(auth()->user()?->role === 'agency_admin' ? 'created_at' : 'starts_on')
                ->label(auth()->user()?->role === 'agency_admin' ? 'Tanggal Pengajuan' : 'Tanggal')
                ->date('d M Y'),
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

    protected function getTableActions(): array
    {
        if (auth()->user()?->role !== 'agency_admin') {
            return [];
        }

        return [
            Tables\Actions\Action::make('view')
                ->label('Lihat')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn (CulturalEvent $record): string => CulturalEventResource::getUrl('edit', ['record' => $record, 'from' => 'dashboard'])),
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
