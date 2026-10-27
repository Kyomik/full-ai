<?php

use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Baris tracking broadcast milik pengirim tidak pernah tampil di bell,
     * jadi tidak pernah ditandai terbaca dan terus menumpuk. Tandai yang lama.
     */
    public function up(): void
    {
        DB::table('notifications')
            ->where('type', AdminBroadcastNotification::class)
            ->whereNull('read_at')
            ->where('data->format', 'admin_broadcast_tracking')
            ->update(['read_at' => now()]);
    }

    public function down(): void
    {
        // Tidak ada yang perlu dikembalikan: baris ini memang tidak pernah ditampilkan.
    }
};
