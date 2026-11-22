<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyamakan database yang dimigrasi sebelum 1 November 2026.
 *
 * Commit 07e78cf dan 74f73c9 mengubah file migrasi lama secara langsung (bukan lewat migrasi baru),
 * sehingga database yang sudah menjalankan migrasi itu masih memakai kolom lama:
 * - about_pages.intro_summary (sekarang intro_description)
 * - facilities.icon (sudah tidak dipakai)
 * - home_pages.hero_primary_* dan hero_secondary_* (sudah tidak dipakai)
 *
 * Setiap langkah dicek dulu, jadi database baru atau yang sudah benar tidak berubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('about_pages', 'intro_summary') && ! Schema::hasColumn('about_pages', 'intro_description')) {
            Schema::table('about_pages', function (Blueprint $table) {
                $table->renameColumn('intro_summary', 'intro_description');
            });
        }

        if (Schema::hasColumn('facilities', 'icon')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }

        $oldHeroColumns = array_values(array_filter(
            ['hero_primary_label', 'hero_primary_url', 'hero_secondary_label', 'hero_secondary_url'],
            fn (string $column) => Schema::hasColumn('home_pages', $column),
        ));

        if ($oldHeroColumns !== []) {
            Schema::table('home_pages', function (Blueprint $table) use ($oldHeroColumns) {
                $table->dropColumn($oldHeroColumns);
            });
        }
    }

    public function down(): void
    {
        // Tidak dikembalikan: skema yang benar sudah ditentukan oleh migrasi awal.
    }
};
