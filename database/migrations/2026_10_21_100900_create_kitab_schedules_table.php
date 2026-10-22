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
        // Jadwal ngaji kitab.
        Schema::create('kitab_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');                            // Safinatun Najah
            $table->string('speaker');
            $table->string('day', 10);                          // App\Enums\Weekday
            $table->string('time');                             // "18.00 WIB (ba'da Maghrib)"
            $table->string('location')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitab_schedules');
    }
};
