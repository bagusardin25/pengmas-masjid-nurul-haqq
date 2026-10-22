<?php

namespace App\Models\Concerns;

use App\Exceptions\SingleRowAlreadyExists;
use Illuminate\Database\Eloquent\Model;

trait HasSingleRow
{
    protected static function bootHasSingleRow(): void
    {
        static::creating(function (Model $model): void {
            $model->setAttribute('singleton', 1);

            if (static::query()->exists()) {
                throw SingleRowAlreadyExists::for($model);
            }
        });
    }

    public static function current(): ?static
    {
        return static::query()->first();
    }
}
