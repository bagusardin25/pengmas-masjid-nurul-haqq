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
        // Tabel 1 baris: Jadwal Kegiatan.
        Schema::create('activity_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('routine_note')->nullable();
            $table->string('kitab_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_pages');
    }
};
