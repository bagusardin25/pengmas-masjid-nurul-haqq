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
        foreach ($this->tables() as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->renameColumn('order', 'sort_order');
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
                $table->renameColumn('sort_order', 'order');
            });
        }
    }

    /**
     * @return list<string>
     */
    private function tables(): array
    {
        return [
            'about_stats',
            'about_roles',
            'organization_members',
            'facilities',
            'routine_activities',
            'kitab_schedules',
            'tpq_pillars',
            'tpq_faqs',
            'tpq_documentations',
            'contact_phones',
        ];
    }
};
