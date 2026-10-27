<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agency_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        $now = now();

        DB::table('agency_types')->insert([
            ['name' => 'Pemerintah', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Komunitas Budaya', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sanggar Seni', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lembaga Pendidikan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Organisasi Masyarakat', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lainnya', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_types');
    }
};
