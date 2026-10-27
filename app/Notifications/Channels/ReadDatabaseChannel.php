<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Notification;

/**
 * Database channel yang langsung menandai baris notifikasi sebagai sudah dibaca.
 *
 * Dipakai untuk baris "tracking" broadcast milik pengirim (Super Admin). Baris
 * itu tidak pernah tampil di bell, sehingga kalau dibiarkan belum dibaca ia
 * menumpuk di himpunan `read_at IS NULL` yang di-scan bell di setiap halaman.
 */
class ReadDatabaseChannel extends DatabaseChannel
{
    protected function buildPayload($notifiable, Notification $notification)
    {
        return array_merge(parent::buildPayload($notifiable, $notification), [
            'read_at' => now(),
        ]);
    }
}
