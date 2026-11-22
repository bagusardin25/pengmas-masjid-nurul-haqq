<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyesuaikan halaman ZISWAF dengan hasil rapat takmir 30 Oktober 2026.
 *
 * - Jenis dana (zakat, sedekah & infak, wakaf, …), program penyaluran sedekah, dan kontak
 *   konfirmasi menjadi daftar yang bisa ditambah, diubah, disembunyikan, atau dihapus.
 * - Laporan keuangan berupa dokumen PDF per kategori (mingguan, kegiatan, pembangunan),
 *   menggantikan laporan angka per bulan. Tabel lama hanya berisi data contoh, jadi dibuat ulang.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ziswaf_pages', function (Blueprint $table) {
            $table->dropColumn(['zakat_note', 'infaq_note', 'sedekah_note', 'wakaf_note', 'confirmation_phone']);
        });

        Schema::table('ziswaf_pages', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('hero_description');         // ringkasan di Beranda
            $table->string('account_note')->nullable()->after('account_holder');    // mis. "Rekening Bendahara Takmir"
            $table->text('infaq_box_description')->nullable();                      // mekanisme kotak infak
            $table->text('report_description')->nullable();                         // pengantar laporan keuangan
        });

        Schema::create('ziswaf_funds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('icon', 30)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ziswaf_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ziswaf_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone', 30);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::dropIfExists('ziswaf_reports');

        Schema::create('ziswaf_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 20)->index();       // mingguan | kegiatan | pembangunan
            $table->date('report_date')->index();          // tanggal laporan, untuk urutan terbaru
            $table->text('description')->nullable();
            $table->foreignId('report_file_id')->constrained('media')->cascadeOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ziswaf_reports');

        Schema::create('ziswaf_reports', function (Blueprint $table) {
            $table->id();
            $table->date('period')->unique();
            $table->unsignedBigInteger('zakat')->default(0);
            $table->unsignedBigInteger('infaq')->default(0);
            $table->unsignedBigInteger('sedekah')->default(0);
            $table->unsignedBigInteger('wakaf')->default(0);
            $table->unsignedBigInteger('disbursed')->default(0);
            $table->foreignId('report_file_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('ziswaf_contacts');
        Schema::dropIfExists('ziswaf_programs');
        Schema::dropIfExists('ziswaf_funds');

        Schema::table('ziswaf_pages', function (Blueprint $table) {
            $table->dropColumn(['summary', 'account_note', 'infaq_box_description', 'report_description']);
        });

        Schema::table('ziswaf_pages', function (Blueprint $table) {
            $table->string('zakat_note')->default('Mal & fitrah');
            $table->string('infaq_note')->default('Kas & operasional');
            $table->string('sedekah_note')->default('Kepedulian sosial');
            $table->string('wakaf_note')->default('Aset produktif umat');
            $table->string('confirmation_phone', 30)->nullable();
        });
    }
};
