<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('report_downloads')) {
            Schema::create('report_downloads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('report_type');
                $table->date('period_from')->nullable();
                $table->date('period_until')->nullable();
                $table->string('status')->default('processing');
                $table->timestamp('downloaded_at')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'report_type', 'period_from', 'period_until'], 'report_downloads_lookup_index');
            });
        } else {
            Schema::table('report_downloads', function (Blueprint $table) {
                $table->index(['user_id', 'report_type', 'period_from', 'period_until'], 'report_downloads_lookup_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('report_downloads');
    }
};
