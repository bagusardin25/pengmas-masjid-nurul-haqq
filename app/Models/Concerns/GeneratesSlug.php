<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::creating(function (Model $model): void {
            if (filled($model->getAttribute('slug'))) {
                return;
            }

            $model->setAttribute('slug', $model->makeUniqueSlug(
                (string) $model->getAttribute($model->slugSource()),
            ));
        });
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected function makeUniqueSlug(string $source): string
    {
        $base = substr(Str::slug($source), 0, 240);

        if ($base === '') {
            $base = Str::lower(class_basename(static::class));
        }

        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $tail = '-'.$suffix;
            $slug = substr($base, 0, 255 - strlen($tail)).$tail;
            $suffix++;
        }

        return $slug;
    }
}
