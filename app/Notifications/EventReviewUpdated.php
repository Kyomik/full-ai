<?php

namespace App\Notifications;

use App\Models\CulturalEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventReviewUpdated extends Notification
{
    use Queueable;

    public function __construct(private readonly CulturalEvent $event) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->event->status === 'approved' ? 'Pengajuan disetujui' : 'Pengajuan perlu diperbaiki',
            'message' => $this->event->status === 'approved'
                ? "Event {$this->event->title} telah disetujui dan ditayangkan."
                : "Event {$this->event->title} ditolak. Alasan: {$this->event->review_note}",
            'event_id' => $this->event->getKey(),
            'status' => $this->event->status,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
