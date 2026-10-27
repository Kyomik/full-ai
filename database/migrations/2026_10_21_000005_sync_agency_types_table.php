<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $names = DB::table('agencies')
            ->whereNotNull('type')
            ->distinct()
            ->pluck('type');

        foreach ($names as $name) {
            DB::table('agency_types')->insertOrIgnore([
                'name' => $name,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Existing master types are intentionally preserved on rollback.
    }
};
