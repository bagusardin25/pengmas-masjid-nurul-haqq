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
        ]);
    }

    protected static function bootHasPublicationStatus(): void
    {
        static::saving(function (Model $model): void {
            if (! in_array('published_at', $model->getFillable(), true)) {
                return;
            }

            $status = $model->getAttribute('status');
            $published = $status === PublicationStatus::Published
                || $status === PublicationStatus::Published->value;

            if ($published && $model->getAttribute('published_at') === null) {
                $model->setAttribute('published_at', now());
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PublicationStatus::Published->value);
    }
}
