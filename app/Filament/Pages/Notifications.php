<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use App\Services\NotificationReadBatch;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Livewire\WithPagination;

class Notifications extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?int $navigationSort = 9;
    protected static ?string $title = 'Notifikasi';
    protected static string $view = 'filament.pages.notifications';

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(auth()->user()?->role, ['super_admin', 'agency_admin'], true);
    }

    public string $search = '';
    public string $typeFilter = '';
    public string $statusFilter = '';
    public string $agencyFilter = 'all';
    public ?array $activeNotification = null;

    public function mount(): void
    {
        if (auth()->user()?->role === 'agency_admin' && request()->query('filter') === 'announcement') {
            $this->agencyFilter = 'announcement';
        }
    }

    public function getTitle(): string
    {
        return auth()->user()?->role === 'agency_admin' ? 'Notifikasi' : 'Data Notifikasi';
    }

    public function getSubheading(): ?string
    {
        return auth()->user()?->role === 'agency_admin'
            ? 'Berikut adalah notifikasi terbaru terkait pengajuan festival & event Anda.'
            : 'Kelola dan kirim notifikasi kepada pengguna sistem.';
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
        $user = auth()->user();

        if ($user?->role === 'agency_admin') {
            return [
                'notifications' => null,
                'agencyNotifications' => $this->getAgencyNotifications(),
                'agencyNotificationCounts' => $this->getAgencyNotificationCounts(),
                'pendingNotificationIds' => app(NotificationReadBatch::class)->pendingIds($user),
            ];
        }

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
            ->when($this->statusFilter !== '', function (Builder $query): Builder {
                return match ($this->statusFilter) {
                    'sent' => $query->where(function (Builder $query): void {
                        $query->where('data->delivery_status', 'sent')
                            ->orWhere(function (Builder $query): void {
                                $query->whereNull('data->delivery_status')->whereNotNull('data->sender_name');
                            });
                    }),
                    'scheduled', 'failed' => $query->where('data->delivery_status', $this->statusFilter),
                    default => $query,
                };
            })
            ->latest()
            ->paginate(5, ['*'], 'notificationsPage');

        return [
            'notifications' => $notifications,
            'agencyNotifications' => null,
            'agencyNotificationCounts' => [],
            'pendingNotificationIds' => [],
        ];
    }

    protected function agencyNotificationQuery(): MorphMany
    {
        return auth()->user()->notifications()->latest();
    }

    protected function getAgencyNotifications(): mixed
    {
        $pendingIds = app(NotificationReadBatch::class)->pendingIds(auth()->user());

        return $this->agencyNotificationQuery()
            ->when($this->agencyFilter === 'unread', function (Builder $query) use ($pendingIds): Builder {
                return $query->whereNull('read_at')
                    ->when($pendingIds !== [], fn (Builder $query): Builder => $query->whereNotIn('id', $pendingIds));
            })
            ->when(in_array($this->agencyFilter, ['submission', 'information', 'announcement', 'system'], true), function (Builder $query): Builder {
                return match ($this->agencyFilter) {
                    'submission' => $query->whereNotNull('data->event_id'),
                    'information' => $query->where('data->category', 'information')->whereNull('data->event_id'),
                    'announcement' => $query->where('data->category', 'announcement')->whereNull('data->event_id'),
                    'system' => $query->whereNull('data->event_id')->where(function (Builder $query): void {
                        $query->where('data->category', 'system')
                            ->orWhereNull('data->category');
                    }),
                };
            })
            ->paginate(5, ['*'], 'agencyNotificationsPage');
    }

    protected function getAgencyNotificationCounts(): array
    {
        $query = $this->agencyNotificationQuery();
        $pendingIds = app(NotificationReadBatch::class)->pendingIds(auth()->user());

        return [
            'all' => (clone $query)->count(),
            'unread' => (clone $query)->whereNull('read_at')
                ->when($pendingIds !== [], fn (Builder $query): Builder => $query->whereNotIn('id', $pendingIds))
                ->count(),
            'submission' => (clone $query)->whereNotNull('data->event_id')->count(),
            'information' => (clone $query)->where('data->category', 'information')->whereNull('data->event_id')->count(),
            'announcement' => (clone $query)->where('data->category', 'announcement')->whereNull('data->event_id')->count(),
            'system' => (clone $query)->whereNull('data->event_id')->where(function (Builder $query): void {
                $query->where('data->category', 'system')
                    ->orWhereNull('data->category');
            })->count(),
        ];
    }

    public function setAgencyFilter(string $filter): void
    {
        if (! in_array($filter, ['all', 'unread', 'submission', 'information', 'announcement', 'system'], true)) {
            return;
        }

        $this->agencyFilter = $filter;
        $this->resetPage('agencyNotificationsPage');
    }

    public function markAllAgencyNotificationsAsRead(): void
    {
        $user = auth()->user();
        $pendingIds = app(NotificationReadBatch::class)->pendingIds($user);
        $notificationIds = $user->unreadNotifications()
            ->when($pendingIds !== [], fn (Builder $query): Builder => $query->whereNotIn('id', $pendingIds))
            ->pluck('id');

        app(NotificationReadBatch::class)->queue($user, $notificationIds);
        $this->resetPage('agencyNotificationsPage');
        $this->dispatch('databaseNotificationsSent');
    }

    public function notificationTypeLabel(array $data): string
    {
        if (! empty($data['event_id'])) {
            return 'Pengajuan';
        }

        return match ($data['category'] ?? 'system') {
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

    public function viewNotification(string $notificationId): void
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($notificationId);

        app(NotificationReadBatch::class)->queue($user, [$notification->id]);
        $this->dispatch('databaseNotificationsSent');
        $data = $notification->data ?? [];
        $this->activeNotification = [
            'id' => $notification->id,
            'title' => $data['title'] ?? 'Pemberitahuan',
            'message' => $data['message'] ?? ($data['body'] ?? 'Tidak ada rincian notifikasi.'),
            'category' => $this->notificationTypeLabel($data),
            'date' => $notification->created_at?->translatedFormat('d F Y, H:i')
                ?? $notification->created_at?->format('d M Y H:i'),
            'event_id' => $data['event_id'] ?? null,
        ];

        $this->dispatch('open-modal', id: 'page-notification-detail-modal');
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
                    Select::make('delivery_mode')
                        ->label('Waktu Pengiriman')
                        ->options([
                            'send_now' => 'Kirim sekarang',
                            'schedule' => 'Jadwalkan pengiriman',
                        ])
                        ->live()
                        ->required()
                        ->default('send_now'),
                    DateTimePicker::make('scheduled_at')
                        ->label('Jadwalkan pada')
                        ->seconds(false)
                        ->minDate(now())
                        ->required(fn (Forms\Get $get): bool => $get('delivery_mode') === 'schedule')
                        ->visible(fn (Forms\Get $get): bool => $get('delivery_mode') === 'schedule'),
                ])
                ->action(function (array $data): void {
                    if (($data['delivery_mode'] ?? 'send_now') === 'schedule') {
                        auth()->user()->notify(new AdminBroadcastNotification(
                            title: $data['title'],
                            message: $data['message'],
                            category: $data['category'],
                            senderName: auth()->user()->name,
                            deliveryStatus: 'scheduled',
                            scheduledAt: $data['scheduled_at'],
                        ));

                        FilamentNotification::make()
                            ->title('Notifikasi berhasil dijadwalkan')
                            ->body('Notifikasi akan dikirim pada ' . $data['scheduled_at'] . '.')
                            ->success()
                            ->send();

                        return;
                    }

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
                        deliveryStatus: 'sent',
                        recipientCount: $recipients->count(),
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
