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
        Schema::create('about_page', function (Blueprint $table) {
            $table->id();
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

        // Ukuran wilayah (270 m², 150 m², 600, Wakaf).
        Schema::create('about_stats', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('label');
            $table->string('icon', 50)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Kegiatan / "Lebih dari tempat sholat".
        Schema::create('about_roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Struktur organisasi.
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->string('position');                         // Ketua, Sekretaris, ...
            $table->string('name');
            $table->string('note')->nullable();                 // "Pengurus inti"
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Fasilitas (dipakai Beranda + Profil).
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 50)->nullable();             // sholat, wudhu, perpustakaan, wifi, parkir, toilet
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('organization_members');
        Schema::dropIfExists('about_roles');
        Schema::dropIfExists('about_stats');
        Schema::dropIfExists('about_page');
    }
};
