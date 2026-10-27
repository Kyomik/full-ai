<?php

namespace App\Services;

use App\Models\User;

class NotificationReadBatch
{
    private const SESSION_KEY_PREFIX = 'pending_notification_reads.';

    public function queue(User $user, iterable $notificationIds): void
    {
        $key = $this->sessionKey($user);
        $pendingIds = session()->get($key, []);

        foreach ($notificationIds as $notificationId) {
            $pendingIds[] = (string) $notificationId;
        }

        session()->put($key, array_values(array_unique($pendingIds)));
    }

    /** @return array<int, string> */
    public function pendingIds(User $user): array
    {
        return array_map('strval', session()->get($this->sessionKey($user), []));
    }

    public function contains(User $user, string $notificationId): bool
    {
        return in_array($notificationId, $this->pendingIds($user), true);
    }

    public function flush(User $user): void
    {
        $notificationIds = session()->pull($this->sessionKey($user), []);

        if ($notificationIds === []) {
            return;
        }

        $user->notifications()
            ->whereIn('id', $notificationIds)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function sessionKey(User $user): string
    {
        return self::SESSION_KEY_PREFIX . $user->getAuthIdentifier();
    }
}
