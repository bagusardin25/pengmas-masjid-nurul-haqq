<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables() as $tableName) {
            $count = DB::table($tableName)->count();

            if ($count > 1) {
                throw new RuntimeException("Tabel {$tableName} berisi {$count} baris. Sisakan satu baris sebelum migrasi ini.");
            }

            Schema::table($tableName, function (Blueprint $table) {
                // Setiap baris harus bernilai 1, jadi tabel hanya bisa menyimpan satu baris.
                $table->unsignedTinyInteger('singleton')->default(1)->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables() as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropUnique(['singleton']);
                $table->dropColumn('singleton');
            });
        }
    }

    /**
     * @return list<string>
     */
    private function tables(): array
    {
        return [
            'home_page',
            'about_page',
            'activity_page',
            'tpq_page',
            'contact',
        ];
    }
};
