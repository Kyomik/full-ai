<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('announcements');
    }

    public function down(): void
    {
        Schema::create('announcements', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->longText('body');
            $table->date('published_on')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }
};
