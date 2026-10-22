<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->text('description')->nullable()->after('slug');
            $table->foreignId('cover_image_id')->nullable()->after('location')->constrained('media')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index()->after('cover_image_id');
        });

        foreach (DB::table('agendas')->orderBy('id')->get() as $agenda) {
            DB::table('agendas')->where('id', $agenda->id)->update([
                'slug' => $this->uniqueSlug($agenda->title, $agenda->id),
                'status' => 'published',
            ]);
        }

        Schema::table('agendas', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cover_image_id');
            $table->dropIndex(['status']);
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'description', 'status']);
        });
    }

    private function uniqueSlug(string $title, int $id): string
    {
        $base = substr(Str::slug($title), 0, 240);
        if ($base === '') {
            $base = 'agenda-'.$id;
        }

        $slug = $base;
        $suffix = 2;

        while (DB::table('agendas')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
};
