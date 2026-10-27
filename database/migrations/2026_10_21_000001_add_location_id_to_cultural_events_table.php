<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('cultural_events', 'location_id')) {
            Schema::table('cultural_events', function (Blueprint $table) {
                $table->foreignId('location_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('locations')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cultural_events', 'location_id')) {
            Schema::table('cultural_events', function (Blueprint $table) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            });
        }
    }
};
