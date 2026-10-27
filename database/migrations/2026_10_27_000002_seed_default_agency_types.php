<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ([
            'Pemerintah',
            'Komunitas Budaya',
            'Sanggar Seni',
            'Lembaga Pendidikan',
            'Organisasi Masyarakat',
            'Lainnya',
        ] as $name) {
            DB::table('agency_types')->insertOrIgnore([
                'name' => $name,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Master data must not be deleted when rolling back a deployment.
    }
};
