<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('agencies', 'province_code')) {
            Schema::table('agencies', function (Blueprint $table) {
                $table->string('province_code')->nullable()->after('address');
                $table->string('regency_code')->nullable()->after('province_code');
                $table->string('district_code')->nullable()->after('regency_code');
                $table->string('village_code')->nullable()->after('district_code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['province_code', 'regency_code', 'district_code', 'village_code']);
        });
    }
};
