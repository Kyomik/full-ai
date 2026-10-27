<?php

use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')
            ->where('type', AdminBroadcastNotification::class)
            ->get()
            ->each(function (object $notification): void {
                $data = json_decode($notification->data, true) ?: [];

                if (($data['format'] ?? null) === 'filament') {
                    return;
                }

                $data['format'] = 'filament';

                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);
            });
    }

    public function down(): void
    {
        // The format field is required by Filament's database notification component.
    }
};
