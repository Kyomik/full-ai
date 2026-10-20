<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CulturalEventResource;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class Notifications extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?int $navigationSort = 9;
    protected static ?string $title = 'Notifikasi';
    protected static string $view = 'filament.pages.notifications';

    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';

    public function getTitle(): string
    {
        return 'Data Notifikasi';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola dan kirim notifikasi kepada pengguna sistem.';
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.notifications-header');
    }

    public function getBreadcrumbs(): array
    {
        return [
            Dashboard::getUrl() => 'Dashboard',
            'Notifikasi',
        ];
    }

    public function getViewData(): array
    {
        $notifications = auth()->user()->notifications()
            ->when($this->search !== '', function (Builder $query): Builder {
                $term = '%' . addcslashes($this->search, '%_') . '%';

                return $query->where(function (Builder $query) use ($term): void {
                    $query->where('data->title', 'like', $term)
                        ->orWhere('data->message', 'like', $term);
                });
            })
            ->when($this->typeFilter !== '', function (Builder $query): Builder {
                return match ($this->typeFilter) {
                    'information' => $query->where(function (Builder $query): void {
                        $query->where('data->category', 'information')
                            ->orWhereNotNull('data->event_id');
                    }),
                    'announcement' => $query->where('data->category', 'announcement'),
                    'system' => $query->where(function (Builder $query): void {
                        $query->where('data->category', 'system')
                            ->orWhere(function (Builder $query): void {
                                $query->whereNull('data->category')->whereNull('data->event_id');
                            });
                    }),
                    default => $query,
                };
            })
            ->when($this->statusFilter === 'unread', fn (Builder $query): Builder => $query->whereNull('read_at'))
            ->when($this->statusFilter === 'read', fn (Builder $query): Builder => $query->whereNotNull('read_at'))
            ->when($this->statusFilter === 'sent', fn (Builder $query): Builder => $query->whereNotNull('data->sender_name'))
            ->latest()
            ->paginate(5, ['*'], 'notificationsPage');

        return ['notifications' => $notifications];
    }

    public function notificationTypeLabel(array $data): string
    {
        $category = $data['category'] ?? (! empty($data['event_id']) ? 'information' : 'system');

        return match ($category) {
            'information' => 'Informasi',
            'announcement' => 'Pengumuman',
            default => 'Sistem',
        };
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->statusFilter = '';
        $this->resetPage('notificationsPage');
    }

    public function updatingSearch(): void
    {
        $this->resetPage('notificationsPage');
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage('notificationsPage');
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage('notificationsPage');
    }

    public function viewNotification(string $notificationId): mixed
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        if (! empty($notification->data['event_id'])) {
            return redirect()->to(CulturalEventResource::getUrl('edit', [
                'record' => $notification->data['event_id'],
            ]));
        }

        return null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendNotification')
                ->label('Kirim Notifikasi')
                ->icon('heroicon-o-plus')
                ->visible(fn (): bool => auth()->user()?->role === 'super_admin')
                ->form([
                    TextInput::make('title')->label('Judul Notifikasi')->required()->maxLength(160),
                    Select::make('category')->label('Jenis Notifikasi')->options([
                        'information' => 'Informasi',
                        'announcement' => 'Pengumuman',
                        'system' => 'Sistem',
                    ])->required()->default('information'),
                    Textarea::make('message')->label('Isi Notifikasi')->required()->rows(4)->maxLength(2000),
                ])
                ->action(function (array $data): void {
                    $recipients = User::query()
                        ->where('role', 'agency_admin')
                        ->where('status', 'active')
                        ->get();

                    if ($recipients->isEmpty()) {
                        FilamentNotification::make()
                            ->title('Tidak ada akun instansi aktif')
                            ->warning()
                            ->send();

                        return;
                    }

                    $broadcast = new AdminBroadcastNotification(
                        title: $data['title'],
                        message: $data['message'],
                        category: $data['category'],
                        senderName: auth()->user()->name,
                    );

                    $recipients->each(fn (User $recipient) => $recipient->notify($broadcast));
                    auth()->user()->notify($broadcast);

                    FilamentNotification::make()
                        ->title('Notifikasi berhasil dikirim')
                        ->body('Notifikasi dikirim ke ' . $recipients->count() . ' akun instansi aktif.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['super_admin', 'agency_admin'], true);
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }
}
