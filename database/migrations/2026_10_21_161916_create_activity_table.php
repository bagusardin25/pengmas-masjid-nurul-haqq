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
        Schema::create('activity_page', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('routine_note')->nullable();
            $table->string('kitab_note')->nullable();
            $table->timestamps();
        });

        // Kegiatan rutin.
        Schema::create('routine_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');                            // Sholat Jumat
            $table->string('schedule')->nullable();             // "Ahad · 19.30 WIB"
            $table->string('badge', 50)->nullable();            // "Setiap hari"
            $table->string('icon', 50)->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Jadwal ngaji kitab.
        Schema::create('kitab_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('kitab_name');                       // Safinatun Najah
            $table->string('ustadz');
            $table->enum('day', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'ahad']);
            $table->string('time');                             // "18.00 WIB (ba'da Maghrib)"
            $table->string('location')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Agenda mendatang (Pengajian Akbar, Maulid Nabi, ...).
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('speaker')->nullable();
            $table->date('date')->index();
            $table->string('time')->nullable();                 // "19.30 WIB – selesai"
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('kitab_schedules');
        Schema::dropIfExists('routine_activities');
        Schema::dropIfExists('activity_page');
    }
};
