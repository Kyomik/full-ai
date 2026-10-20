<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('gallery_items', 'uploaded_by_id')) {
            Schema::table('gallery_items', function (Blueprint $table): void {
                $table->foreignId('uploaded_by_id')->nullable()->after('cultural_event_id')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('gallery_items', 'uploaded_by_id')) {
            Schema::table('gallery_items', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('uploaded_by_id');
            });
        }
    }
};
