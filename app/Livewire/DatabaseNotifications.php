<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\NotificationReadBatch;
use Filament\Facades\Filament;
use Filament\Notifications\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Contracts\Pagination\Paginator;

class DatabaseNotifications extends BaseDatabaseNotifications
{
    public static bool $isPaginated = false;

    public ?array $activeNotification = null;

    public function getNotifications(): DatabaseNotificationCollection | Paginator
    {
        return $this->getNotificationsQuery()
            ->select(['id', 'data', 'read_at', 'created_at'])
            ->latest()
            ->limit(5)
            ->get();
    }

    public function getUser(): Model | Authenticatable | null
    {
        return Filament::auth()->user();
    }

    public function getPollingInterval(): ?string
    {
        return Filament::getDatabaseNotificationsPollingInterval();
    }

    public function getTrigger(): View
    {
        return view('filament-panels::components.topbar.database-notifications-trigger');
    }

    public function getNotificationsQuery(): Builder | Relation
    {
        $user = $this->getUser();

        if (! $user instanceof User) {
            abort(401);
        }

        $pendingIds = app(NotificationReadBatch::class)->pendingIds($user);

        return $user->notifications()
            ->where(function ($query): void {
                $query->whereNull('data->format')
                    ->orWhere('data->format', '!=', 'admin_broadcast_tracking');
            })
            ->where(function ($query) use ($pendingIds): void {
                $query->whereNull('read_at');

                if ($pendingIds !== []) {
                    $query->orWhereIn('id', $pendingIds);
                }
            });
    }

    public function getUnreadNotificationsQuery(): Builder | Relation
    {
        $user = $this->getUser();

        if (! $user instanceof User) {
            abort(401);
        }

        $pendingIds = app(NotificationReadBatch::class)->pendingIds($user);

        return $user->notifications()
            ->where(function ($query): void {
                $query->whereNull('data->format')
                    ->orWhere('data->format', '!=', 'admin_broadcast_tracking');
            })
            ->whereNull('read_at')
            ->when($pendingIds !== [], fn ($query) => $query->whereNotIn('id', $pendingIds));
    }

    public function openNotification(string $id): void
    {
        $user = $this->getUser();
        if (! $user) {
            return;
        }

        $notification = $user->notifications()->find($id);
        if (! $notification) {
            return;
        }

        // Simpan perubahan baca sampai logout, tetapi pertahankan item di panel
        // bell untuk sisa sesi aktif.
        app(NotificationReadBatch::class)->queue($user, [$notification->id]);
        $this->dispatch('databaseNotificationsSent');

        $data = $notification->data ?? [];
        $this->activeNotification = [
            'id' => $notification->id,
            'title' => $data['title'] ?? 'Pemberitahuan',
            'message' => $data['message'] ?? ($data['body'] ?? 'Tidak ada rincian notifikasi.'),
            'category' => ! empty($data['event_id']) ? 'Pengajuan' : match ($data['category'] ?? 'system') {
                'information' => 'Informasi',
                'announcement' => 'Pengumuman',
                default => 'Sistem',
            },
            'date' => $notification->created_at?->translatedFormat('d F Y, H:i') ?? $notification->created_at?->format('d M Y H:i'),
            'event_id' => $data['event_id'] ?? null,
        ];

        $this->dispatch('open-modal', id: 'sidebar-notification-detail-modal');
    }

    public function isNotificationReadForSession(string $notificationId, bool $databaseRead): bool
    {
        $user = $this->getUser();

        return $databaseRead || ($user instanceof User
            && app(NotificationReadBatch::class)->contains($user, $notificationId));
    }
}
