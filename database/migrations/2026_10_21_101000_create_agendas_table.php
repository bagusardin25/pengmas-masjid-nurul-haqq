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
        // Agenda mendatang (Pengajian Akbar, Maulid Nabi, ...).
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('speaker')->nullable();
            $table->date('date')->index();
            $table->string('time')->nullable();                 // "19.30 WIB – selesai"
            $table->string('location')->nullable();
            $table->foreignId('cover_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
