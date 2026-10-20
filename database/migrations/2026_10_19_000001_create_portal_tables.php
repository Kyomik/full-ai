<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('type'); $table->string('email')->unique();
            $table->string('phone', 30)->nullable(); $table->text('address')->nullable(); $table->string('logo')->nullable();
            $table->string('status')->default('active'); $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('role')->default('agency_admin')->after('password');
            $table->string('status')->default('active')->after('role');
        });
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->string('name')->unique(); $table->text('description')->nullable(); $table->timestamps();
        });
        Schema::create('cultural_events', function (Blueprint $table) {
            $table->id(); $table->foreignId('agency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title'); $table->string('slug')->unique(); $table->string('theme')->nullable();
            $table->longText('description'); $table->date('starts_on'); $table->date('ends_on'); $table->time('starts_at')->nullable();
            $table->string('venue'); $table->text('address'); $table->string('province_code')->nullable();
            $table->string('regency_code')->nullable(); $table->string('district_code')->nullable(); $table->string('village_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable(); $table->decimal('longitude', 10, 7)->nullable();
            $table->string('poster')->nullable(); $table->string('status')->default('pending'); $table->text('review_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('reviewed_at')->nullable();
            $table->timestamps(); $table->index(['status', 'starts_on']);
        });
        Schema::create('event_documents', function (Blueprint $table) {
            $table->id(); $table->foreignId('cultural_event_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->string('file_path'); $table->timestamps();
        });
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('title');
            $table->string('slug')->unique(); $table->text('excerpt')->nullable(); $table->longText('body');
            $table->string('image')->nullable(); $table->timestamp('published_at')->nullable(); $table->timestamps();
        });
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id(); $table->foreignId('cultural_event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title'); $table->string('file_path'); $table->string('type')->default('photo');
            $table->boolean('is_published')->default(true); $table->timestamps();
        });
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->longText('body'); $table->date('published_on')->nullable();
            $table->boolean('is_published')->default(false); $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements'); Schema::dropIfExists('gallery_items'); Schema::dropIfExists('articles');
        Schema::dropIfExists('event_documents'); Schema::dropIfExists('cultural_events'); Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) { $table->dropConstrainedForeignId('agency_id'); $table->dropColumn(['role', 'status']); });
        Schema::dropIfExists('agencies');
    }
};
