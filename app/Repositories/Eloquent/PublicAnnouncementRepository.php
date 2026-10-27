<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use App\Repositories\Contracts\PublicAnnouncementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PublicAnnouncementRepository implements PublicAnnouncementRepositoryInterface
{
    public function latestPublished(int $limit): Collection
    {
        return $this->query()
            ->limit($limit)
            ->get()
            ->map(fn (DatabaseNotification $notification) => $this->toPortalItem($notification));
    }

    public function paginatePublished(int $perPage): LengthAwarePaginator
    {
        $announcements = $this->query()->paginate($perPage);
        $announcements->setCollection(
            $announcements->getCollection()->map(fn (DatabaseNotification $notification) => $this->toPortalItem($notification)),
        );

        return $announcements;
    }

    private function query()
    {
        return DatabaseNotification::query()
            ->where('type', AdminBroadcastNotification::class)
            ->where('notifiable_type', User::class)
            ->where('data->format', 'admin_broadcast_tracking')
            ->where('data->category', 'announcement')
            ->where('data->delivery_status', 'sent')
            ->orderByDesc('data->sent_at');
    }

    private function toPortalItem(DatabaseNotification $notification): object
    {
        return (object) [
            'title' => $notification->data['title'] ?? 'Pengumuman',
            'body' => $notification->data['message'] ?? '',
            'published_on' => isset($notification->data['sent_at'])
                ? Carbon::parse($notification->data['sent_at'])
                : $notification->created_at,
        ];
    }
}
