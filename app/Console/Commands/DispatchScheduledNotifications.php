<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DispatchScheduledNotifications extends Command
{
    protected $signature = 'notifications:dispatch-scheduled';

    protected $description = 'Kirim notifikasi broadcast yang sudah masuk waktu jadwalnya';

    public function handle(): int
    {
        $scheduled = DB::table('notifications')
            ->where('type', AdminBroadcastNotification::class)
            ->where('data->delivery_status', 'scheduled')
            ->get();

        foreach ($scheduled as $row) {
            $data = json_decode($row->data, true) ?: [];
            $scheduledAt = ! empty($data['scheduled_at']) ? Carbon::parse($data['scheduled_at']) : null;

            if (! $scheduledAt || $scheduledAt->isFuture()) {
                continue;
            }

            $data['delivery_status'] = 'processing';
            DB::table('notifications')->where('id', $row->id)->update(['data' => json_encode($data)]);

            try {
                $sender = User::find($row->notifiable_id);
                $recipients = User::query()
                    ->where('role', 'agency_admin')
                    ->where('status', 'active')
                    ->get();

                if (! $sender || $recipients->isEmpty()) {
                    throw new \RuntimeException('Tidak ada akun instansi aktif.');
                }

                $broadcast = new AdminBroadcastNotification(
                    title: $data['title'] ?? 'Pemberitahuan',
                    message: $data['message'] ?? '',
                    category: $data['category'] ?? 'information',
                    senderName: $data['sender_name'] ?? $sender->name,
                    deliveryStatus: 'sent',
                    recipientCount: $recipients->count(),
                );

                $recipients->each(fn (User $recipient) => $recipient->notify($broadcast));

                $data['delivery_status'] = 'sent';
                $data['sent_at'] = now()->toIso8601String();
                $data['recipient_count'] = $recipients->count();
                $data['failure_message'] = null;
            } catch (\Throwable $exception) {
                $data['delivery_status'] = 'failed';
                $data['failure_message'] = $exception->getMessage();
            }

            DB::table('notifications')->where('id', $row->id)->update(['data' => json_encode($data)]);
        }

        return self::SUCCESS;
    }
}
