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
        // Tabel 1 baris (dipakai Beranda, Kontak, Footer, tombol WA).
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('email')->nullable();
            $table->string('instagram')->nullable();            // username tanpa @
            $table->text('address')->nullable();
            $table->string('maps_url')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->string('service_hours')->nullable();        // "Senin–Jumat · 09.00–15.00 WIB"
            $table->string('youtube_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
