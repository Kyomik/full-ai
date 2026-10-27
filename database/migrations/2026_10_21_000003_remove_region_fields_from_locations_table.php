<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $columns = ['province_code', 'regency_code', 'district_code', 'village_code'];
        $existing = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('locations', $column)));

        if ($existing !== []) {
            Schema::table('locations', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('province_code')->nullable();
            $table->string('regency_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('village_code')->nullable();
        });
    }
};
