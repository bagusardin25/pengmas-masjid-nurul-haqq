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
        Schema::table('home_page', function (Blueprint $table) {
            $table->text('hero_arabic_text')->nullable()->change();
        });

        Schema::table('home_page', function (Blueprint $table) {
            $table->text('hero_translation')->nullable()->after('hero_arabic_text');
            $table->string('hero_citation')->nullable()->after('hero_translation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_page', function (Blueprint $table) {
            $table->dropColumn(['hero_translation', 'hero_citation']);
        });

        Schema::table('home_page', function (Blueprint $table) {
            $table->string('hero_arabic_text')->nullable()->change();
        });
    }
};
