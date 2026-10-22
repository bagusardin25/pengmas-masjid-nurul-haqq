<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Video dari link YouTube; thumbnail dari youtube_id.
        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_url');
            $table->string('youtube_id', 20);                   // di-parse dari URL saat simpan
            $table->string('title');
            $table->string('duration', 20)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('gallery_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('speaker')->nullable();
            $table->date('date')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_videos');
    }
};
