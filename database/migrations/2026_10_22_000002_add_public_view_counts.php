<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('published_at');
        });

        Schema::table('cultural_events', function (Blueprint $table) {
            $table->unsignedBigInteger('gallery_views_count')->default(0)->after('ends_on');
        });
    }

    public function down(): void
    {
        Schema::table('cultural_events', function (Blueprint $table) {
            $table->dropColumn('gallery_views_count');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};
