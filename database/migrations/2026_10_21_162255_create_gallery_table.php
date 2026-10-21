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
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained('media')->cascadeOnDelete();
            $table->string('title');
            $table->string('category', 50)->nullable();         // kajian-rutin, sholat-berjamaah, kegiatan-tpq, pengajian-ahad-malam
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
            $table->index(['category', 'date']);
        });

        // Video dari link YouTube; thumbnail dari youtube_id.
        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_url');
            $table->string('youtube_id', 20);                   // di-parse dari URL saat simpan
            $table->string('title');
            $table->string('category', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('speaker')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_videos');
        Schema::dropIfExists('gallery_photos');
    }
};
