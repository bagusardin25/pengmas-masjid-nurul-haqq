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
        // Tabel 1 baris. Total = zakat + infaq + sedekah + wakaf (dihitung di model, tidak disimpan).
        Schema::create('ziswaf', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('period')->nullable();               // "Periode Mei 2026"
            $table->unsignedBigInteger('zakat')->default(0);     // Rupiah
            $table->unsignedBigInteger('infaq')->default(0);
            $table->unsignedBigInteger('sedekah')->default(0);
            $table->unsignedBigInteger('wakaf')->default(0);
            // Rekening
            $table->string('bank_name')->nullable();
            $table->string('account_number', 50)->nullable();   // string agar nol depan aman
            $table->string('account_holder')->nullable();
            // QRIS
            $table->foreignId('qris_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('qris_note')->nullable();
            $table->string('confirmation_phone', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ziswaf');
    }
};
