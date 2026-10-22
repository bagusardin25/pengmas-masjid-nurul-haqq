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
        // Tabel 1 baris: halaman ZISWAF, rekening, dan QRIS. Angka per bulan ada di ziswaf_reports.
        Schema::create('ziswaf_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('singleton')->default(1)->unique();
            $table->string('hero_title');
            $table->text('hero_description')->nullable();
            $table->string('zakat_note')->default('Mal & fitrah');
            $table->string('infaq_note')->default('Kas & operasional');
            $table->string('sedekah_note')->default('Kepedulian sosial');
            $table->string('wakaf_note')->default('Aset produktif umat');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ziswaf_pages');
    }
};
