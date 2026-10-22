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
        // Tabel 1 baris: Program TPQ.
        Schema::create('tpq_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->text('summary')->nullable();                // ringkasan di Beranda
            // Informasi program
            $table->text('info_description')->nullable();
            $table->string('info_age_range')->nullable();       // "6–15 tahun"
            $table->string('info_schedule')->nullable();        // "Senin–Kamis · 15.30–17.00 WIB"
            $table->string('info_fee')->nullable();             // "Rp80.000 / bulan"
            // Kotak pendaftaran
            $table->string('registration_title')->nullable();
            $table->text('registration_description')->nullable();
            $table->string('registration_phone', 30)->nullable();
            $table->string('registration_form_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tpq_pages');
    }
};
