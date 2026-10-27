<?php

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')
            ->where('type', AdminBroadcastNotification::class)
            ->where('notifiable_type', User::class)
            ->get()
            ->each(function (object $notification): void {
                $recipient = User::find($notification->notifiable_id);

                if (! $recipient) {
                    return;
                }

                $data = json_decode($notification->data, true) ?: [];
                $data['format'] = $recipient->role === 'agency_admin'
                    ? 'filament'
                    : 'admin_broadcast_tracking';

                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);
            });
    }

    public function down(): void
    {
        // Keep the separated format because it prevents senders from seeing their own broadcast in the bell.
    }
};
