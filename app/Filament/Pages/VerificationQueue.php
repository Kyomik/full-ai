<?php

namespace App\Filament\Pages;

use App\Models\CulturalEvent;
use App\Models\Agency;
use App\Models\User;
use App\Notifications\EventReviewUpdated;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class VerificationQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Verifikasi Pengajuan';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Verifikasi Pengajuan';
    protected static string $view = 'filament.pages.verification-queue';

    public string $agencyFilter = '';
    public string $statusFilter = '';
    public string $submittedFrom = '';
    public string $submittedUntil = '';

    public static function getNavigationGroup(): ?string { return null; }
    public static function canAccess(): bool { return auth()->user()?->role === 'super_admin'; }

    public function getSubheading(): ?string
    {
        return 'Kelola dan verifikasi pengajuan festival & event dari instansi.';
    }

    public function getAgencyOptions(): array
    {
        return Agency::query()->where('status', 'active')->orderBy('name')->pluck('name', 'id')->all();
    }

    public function resetVerificationFilters(): void
    {
        $this->agencyFilter = '';
        $this->statusFilter = '';
        $this->submittedFrom = '';
        $this->submittedUntil = '';
        $this->tableSearch = '';
        $this->resetPage();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CulturalEvent::query()->with(['agency', 'category'])->latest())
            ->modifyQueryUsing(function (Builder $query): Builder {
                return $query
                    ->when($this->agencyFilter !== '', fn (Builder $query): Builder => $query->where('agency_id', $this->agencyFilter))
                    ->when($this->statusFilter !== '', fn (Builder $query): Builder => $query->where('status', $this->statusFilter))
                    ->when($this->submittedFrom !== '', fn (Builder $query): Builder => $query->whereDate('created_at', '>=', $this->submittedFrom))
                    ->when($this->submittedUntil !== '', fn (Builder $query): Builder => $query->whereDate('created_at', '<=', $this->submittedUntil));
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('No')->rowIndex(),
                Tables\Columns\ViewColumn::make('event_summary')
                    ->label('Judul Event')
                    ->view('filament.tables.columns.verification-event-summary')
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('title', $direction)),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->visible(false)
                    ->sortable()
                    ->description(fn (CulturalEvent $record): string => implode(' • ', array_filter([
                        $record->category?->name,
                        $record->venue,
                    ]))),
                Tables\Columns\TextColumn::make('agency.name')->label('Instansi')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('starts_on')
                    ->label('Tanggal Pelaksanaan')
                    ->formatStateUsing(fn (CulturalEvent $record): string => $record->starts_on?->format('d M Y') . ($record->ends_on && !$record->starts_on->isSameDay($record->ends_on) ? ' - ' . $record->ends_on->format('d M Y') : ''))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu Verifikasi',
                    })
                    ->description(fn (CulturalEvent $record): ?string => $record->reviewed_at ? 'Super Admin' : null)
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->actionsColumnLabel('Aksi')
            ->actionsAlignment('start')
            ->searchable()
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (CulturalEvent $record): string => \App\Filament\Resources\CulturalEventResource::getUrl('edit', ['record' => $record, 'from' => 'verification']))
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make('approve')->label('Setujui')->icon('heroicon-o-check')->color('success')->visible(fn (CulturalEvent $record): bool => $record->status === 'pending')->requiresConfirmation()->action(function (CulturalEvent $record) {
                    $record->update(['status' => 'approved', 'reviewed_by' => auth()->id(), 'reviewed_at' => now(), 'review_note' => null]);
                    $record->agency->users()->each(fn (User $user) => $user->notify(new EventReviewUpdated($record)));
                    Notification::make()->title('Pengajuan disetujui')->success()->send();
                }),
                Tables\Actions\Action::make('reject')->label('Tolak')->icon('heroicon-o-x-mark')->color('danger')->visible(fn (CulturalEvent $record): bool => $record->status === 'pending')->form([Textarea::make('review_note')->label('Alasan penolakan')->required()])->action(function (CulturalEvent $record, array $data) {
                    $record->update(['status' => 'rejected', 'reviewed_by' => auth()->id(), 'reviewed_at' => now(), 'review_note' => $data['review_note']]);
                    $record->agency->users()->each(fn (User $user) => $user->notify(new EventReviewUpdated($record)));
                    Notification::make()->title('Pengajuan ditolak')->warning()->send();
                }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada pengajuan')
            ->emptyStateDescription('Pengajuan dari instansi akan muncul di daftar ini.');
    }
}
