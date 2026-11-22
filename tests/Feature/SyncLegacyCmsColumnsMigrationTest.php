<?php

use App\Models\AboutPage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function runSyncLegacyCmsColumnsMigration(): void
{
    $migration = require database_path('migrations/2026_11_13_100000_sync_legacy_cms_columns.php');
    $migration->up();
}

test('old databases get the current cms columns without losing data', function () {
    // Kembalikan skema ke kondisi database yang dimigrasi sebelum 1 November 2026.
    Schema::table('about_pages', fn (Blueprint $table) => $table->renameColumn('intro_description', 'intro_summary'));
    Schema::table('facilities', fn (Blueprint $table) => $table->string('icon', 50)->nullable());
    Schema::table('home_pages', function (Blueprint $table) {
        $table->string('hero_primary_label')->nullable();
        $table->string('hero_primary_url')->nullable();
        $table->string('hero_secondary_label')->nullable();
        $table->string('hero_secondary_url')->nullable();
    });

    DB::table('about_pages')->insert([
        'singleton' => 1,
        'hero_title' => 'Profil lama',
        'intro_summary' => 'Ringkasan yang sudah ada',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    runSyncLegacyCmsColumnsMigration();

    expect(Schema::hasColumn('about_pages', 'intro_description'))->toBeTrue()
        ->and(Schema::hasColumn('about_pages', 'intro_summary'))->toBeFalse()
        ->and(Schema::hasColumn('facilities', 'icon'))->toBeFalse()
        ->and(Schema::hasColumn('home_pages', 'hero_primary_label'))->toBeFalse()
        ->and(Schema::hasColumn('home_pages', 'hero_secondary_url'))->toBeFalse()
        ->and(AboutPage::current()->intro_description)->toBe('Ringkasan yang sudah ada');
});

test('databases that already match are left unchanged', function () {
    $before = [
        Schema::getColumnListing('about_pages'),
        Schema::getColumnListing('facilities'),
        Schema::getColumnListing('home_pages'),
    ];

    runSyncLegacyCmsColumnsMigration();

    expect([
        Schema::getColumnListing('about_pages'),
        Schema::getColumnListing('facilities'),
        Schema::getColumnListing('home_pages'),
    ])->toBe($before);
});
