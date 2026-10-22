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
        // Laporan per bulan. Nominal dalam Rupiah.
        Schema::create('ziswaf_reports', function (Blueprint $table) {
            $table->id();
            // Hari pertama bulan laporan, misalnya 2026-05-01 untuk Mei 2026.
            $table->date('period')->unique();
            $table->unsignedBigInteger('zakat')->default(0);
            $table->unsignedBigInteger('infaq')->default(0);
            $table->unsignedBigInteger('sedekah')->default(0);
            $table->unsignedBigInteger('wakaf')->default(0);
            // Total penyaluran bulan itu. Saldo dihitung, tidak disimpan.
            $table->unsignedBigInteger('disbursed')->default(0);
            $table->foreignId('report_file_id')->nullable()->constrained('media')->nullOnDelete();
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
        Schema::dropIfExists('ziswaf_reports');
    }
};
