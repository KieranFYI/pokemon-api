<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use App\Models\Log;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Model
 */
trait Observable
{
    public static function bootObservable()
    {
        static::updated(function (Model $model) {
            static::observeChanges($model, 'updated');
        });

        static::created(function (Model $model) {
            static::observeChanges($model, 'created');
        });

        static::deleted(function (Model $model) {
            static::observeChanges($model, 'deleted');
        });
    }

    /**
     * @param Model $model
     * @param string $action
     *
     * @return void
     */
    public static function observeChanges(Model $model, string $action): void
    {
        $log = new Log([
            'action' => $action,
            'new' => $action !== 'deleted' ? static::cleanKeys($model, $model->getAttributes()) : null,
            'old' => $action !== 'created' ? static::cleanKeys($model, $model->getOriginal()) : null,
            'changes' => $action === 'updated' ? static::cleanKeys($model, $model->getChanges()) : null,
        ]);
        $log->model()->associate($model);

        if (!is_null(Auth::user()) && !is_null(Auth::user()->getAuthIdentifier())) {
            $log->user()->associate(Auth::user());
        }

        $log->save();
    }

    /**
     * Removes hidden keys, so they are not stored in the database.
     *
     * @param Model $model
     * @param array $array
     * @return array
     */
    private static function cleanKeys(Model $model, array $array)
    {
        return array_diff_key($array, array_flip($model->getHidden()));
    }

    /**
     * Get the entity's logs.
     *
     * @return MorphMany
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'model');
    }
}
