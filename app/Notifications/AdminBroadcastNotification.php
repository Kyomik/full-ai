<?php

namespace App\Notifications;

use App\Notifications\Channels\ReadDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminBroadcastNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $category,
        private readonly string $senderName,
        private readonly string $deliveryStatus = 'sent',
        private readonly ?string $scheduledAt = null,
        private readonly ?int $recipientCount = null,
        private readonly ?string $failureMessage = null,
    ) {}

    public function via(object $notifiable): array
    {
        // Penerima (admin instansi) memakai channel biasa. Baris tracking milik
        // pengirim langsung ditandai terbaca agar tidak menumpuk di query bell.
        return ($notifiable->role ?? null) === 'agency_admin'
            ? ['database']
            : [ReadDatabaseChannel::class];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'format' => ($notifiable->role ?? null) === 'agency_admin'
                ? 'filament'
                : 'admin_broadcast_tracking',
            'title' => $this->title,
            'message' => $this->message,
            'category' => $this->category,
            'sender_name' => $this->senderName,
            'delivery_status' => $this->deliveryStatus,
            'scheduled_at' => $this->scheduledAt,
            'sent_at' => $this->deliveryStatus === 'sent' ? now()->toIso8601String() : null,
            'recipient_count' => $this->recipientCount,
            'failure_message' => $this->failureMessage,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
