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
        // Kegiatan rutin.
        Schema::create('routine_activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');                            // Sholat Jumat
            $table->string('schedule')->nullable();             // "Ahad · 19.30 WIB"
            $table->string('badge', 50)->nullable();            // "Setiap hari"
            $table->string('icon', 50)->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_activities');
    }
};
