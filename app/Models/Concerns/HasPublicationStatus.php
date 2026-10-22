<?php

namespace App\Models\Concerns;

use App\Enums\PublicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasPublicationStatus
{
    public function initializeHasPublicationStatus(): void
    {
        $this->mergeCasts([
            'status' => PublicationStatus::class,
            'published_at' => 'datetime',
        ]);
    }

    protected static function bootHasPublicationStatus(): void
    {
        static::saving(function (Model $model): void {
            if ($model->getAttribute('status') === PublicationStatus::Published
                && $model->getAttribute('published_at') === null) {
                $model->setAttribute('published_at', now());
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value);
    }
}
