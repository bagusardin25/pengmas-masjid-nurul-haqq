<?php

namespace App\Models\Concerns;

use App\Exceptions\SingletonAlreadyExists;
use Illuminate\Database\Eloquent\Model;

trait HasSingleton
{
    protected static function bootHasSingleton(): void
    {
        static::creating(function (Model $model): void {
            $model->setAttribute('singleton', 1);

            if (static::query()->exists()) {
                throw SingletonAlreadyExists::for($model);
            }
        });
    }

    public static function current(): ?static
    {
        return static::query()->first();
    }
}
