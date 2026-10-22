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
        // Tabel 1 baris: hero Beranda.
        Schema::create('home_pages', function (Blueprint $table) {
            $table->id();
            // Selalu bernilai 1 dan unik, jadi tabel hanya bisa menyimpan satu baris.
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->foreignId('hero_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('hero_arabic_text')->nullable();
            $table->text('hero_translation')->nullable();
            $table->string('hero_citation')->nullable();
            $table->string('hero_primary_label')->nullable();
            $table->string('hero_primary_url')->nullable();
            $table->string('hero_secondary_label')->nullable();
            $table->string('hero_secondary_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
