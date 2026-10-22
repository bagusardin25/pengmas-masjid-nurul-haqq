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
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();

        DB::table('gallery_categories')->insert([
            ['name' => 'Kajian rutin', 'slug' => 'kajian-rutin', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sholat berjamaah', 'slug' => 'sholat-berjamaah', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kegiatan TPQ', 'slug' => 'kegiatan-tpq', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pengajian Ahad malam', 'slug' => 'pengajian-ahad-malam', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('gallery_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('cover_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['category_id', 'date']);
        });

        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->foreignId('album_id')->nullable()->after('id')->constrained('gallery_albums')->cascadeOnDelete();
            $table->string('caption')->nullable()->after('image_id');
            $table->unsignedInteger('sort_order')->default(0)->after('caption');
        });

        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('title')->constrained('gallery_categories')->nullOnDelete();
            $table->string('duration', 20)->nullable()->after('title');
            $table->string('status', 20)->default('draft')->index()->after('date');
            $table->unsignedInteger('sort_order')->default(0)->after('status');
        });

        $this->movePhotosIntoAlbums();

        DB::table('gallery_videos')->update(['status' => 'published']);
        $this->mapVideoCategories();

        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropIndex(['category', 'date']);
            $table->dropColumn(['title', 'category', 'description', 'date', 'location']);
        });

        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable(false)->change();
        });

        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('category', 50)->nullable();
            $table->text('description')->nullable();
            $table->date('date')->nullable();
            $table->string('location')->nullable();
        });

        $albums = DB::table('gallery_albums')->get()->keyBy('id');
        $categorySlugs = DB::table('gallery_categories')->pluck('slug', 'id');

        foreach (DB::table('gallery_photos')->orderBy('id')->get() as $photo) {
            $album = $albums->get($photo->album_id);

            DB::table('gallery_photos')->where('id', $photo->id)->update([
                'title' => $album->title ?? 'Foto',
                'category' => $album && $album->category_id ? ($categorySlugs[$album->category_id] ?? null) : null,
                'description' => $album->description ?? null,
                'date' => $album->date ?? null,
                'location' => $album->location ?? null,
            ]);
        }

        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('album_id');
            $table->dropColumn(['caption', 'sort_order']);
        });

        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->index(['category', 'date']);
        });

        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('title');
        });

        foreach (DB::table('gallery_videos')->orderBy('id')->get() as $video) {
            DB::table('gallery_videos')->where('id', $video->id)->update([
                'category' => $video->category_id ? ($categorySlugs[$video->category_id] ?? null) : null,
            ]);
        }

        Schema::table('gallery_videos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropIndex(['status']);
            $table->dropColumn(['duration', 'status', 'sort_order']);
        });

        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('gallery_categories');
    }

    private function movePhotosIntoAlbums(): void
    {
        $categories = DB::table('gallery_categories')->pluck('id', 'slug')->all();
        $photos = DB::table('gallery_photos')->orderBy('id')->get();

        foreach ($photos as $index => $photo) {
            $albumId = DB::table('gallery_albums')->insertGetId([
                'title' => $photo->title,
                'slug' => $this->uniqueSlug($photo->title, $photo->id),
                'category_id' => $this->categoryId($photo->category, $categories),
                'description' => $photo->description,
                'date' => $photo->date,
                'location' => $photo->location,
                'cover_image_id' => $photo->image_id,
                'status' => 'published',
                'sort_order' => $index,
                'created_at' => $photo->created_at,
                'updated_at' => $photo->updated_at,
            ]);

            DB::table('gallery_photos')->where('id', $photo->id)->update([
                'album_id' => $albumId,
                'sort_order' => 0,
            ]);
        }
    }

    private function mapVideoCategories(): void
    {
        if (! Schema::hasColumn('gallery_videos', 'category')) {
            return;
        }

        $categories = DB::table('gallery_categories')->pluck('id', 'slug')->all();

        foreach (DB::table('gallery_videos')->orderBy('id')->get() as $video) {
            DB::table('gallery_videos')->where('id', $video->id)->update([
                'category_id' => $this->categoryId($video->category, $categories),
            ]);
        }
    }

    /**
     * @param  array<string, int>  $categories
     */
    private function categoryId(?string $label, array &$categories): ?int
    {
        $label = trim((string) $label);

        if ($label === '') {
            return null;
        }

        $slug = substr(Str::slug($label), 0, 240);

        if ($slug !== '' && isset($categories[$slug])) {
            return (int) $categories[$slug];
        }

        if ($slug === '') {
            $slug = 'kategori-'.substr(sha1($label), 0, 12);
        }

        $id = DB::table('gallery_categories')->insertGetId([
            'name' => $label,
            'slug' => $slug,
            'sort_order' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $categories[$slug] = $id;

        return (int) $id;
    }

    private function uniqueSlug(string $title, int $id): string
    {
        $base = substr(Str::slug($title), 0, 240);
        if ($base === '') {
            $base = 'album-'.$id;
        }

        $slug = $base;
        $suffix = 2;

        while (DB::table('gallery_albums')->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
};
