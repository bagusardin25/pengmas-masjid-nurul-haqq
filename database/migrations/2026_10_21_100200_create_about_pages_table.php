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
        // Tabel 1 baris: About / Profil.
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->foreignId('hero_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('hero_badge')->nullable();          // "Sejak 2008"
            $table->string('intro_title')->nullable();
            $table->text('intro_summary')->nullable();          // juga tampil di Beranda
            $table->string('history_title')->default('Sejarah');
            $table->longText('history_content')->nullable();    // rich text / HTML
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
