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
        Schema::create('tpq_page', function (Blueprint $table) {
            $table->id();
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

        // Pilar program: Iqro', Tahfidz, Akhlak, Kelas reguler.
        Schema::create('tpq_pillars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('tpq_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('tpq_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained('media')->cascadeOnDelete();
            $table->string('caption')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tpq_documentations');
        Schema::dropIfExists('tpq_faqs');
        Schema::dropIfExists('tpq_pillars');
        Schema::dropIfExists('tpq_page');
    }
};
