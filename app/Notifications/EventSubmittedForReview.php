<?php

namespace App\Notifications;

use App\Models\CulturalEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventSubmittedForReview extends Notification
{
    use Queueable;

    public function __construct(private readonly CulturalEvent $event) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $agencyName = $this->event->agency?->name ?? 'Penyelenggara';
        $message = "{$agencyName} mengajukan event {$this->event->title} untuk diverifikasi.";

        return [
            'format' => 'filament',
            'title' => 'Pengajuan event baru',
            'body' => $message,
            'message' => $message,
            'event_id' => $this->event->getKey(),
            'status' => $this->event->status,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
