<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->string('visitor_hash', 64);
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('last_seen_at')->index();
            $table->timestamps();

            $table->unique(['visit_date', 'visitor_hash']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
