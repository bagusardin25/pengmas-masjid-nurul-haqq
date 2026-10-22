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
        // Struktur organisasi.
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->string('position');                         // Ketua, Sekretaris, ...
            $table->string('name');
            $table->string('note')->nullable();                 // "Pengurus inti"
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
