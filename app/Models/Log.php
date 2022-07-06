<?php

namespace App\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Log extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['action', 'new', 'old', 'changes'];

    /**
     * @var string[]
     */
    protected $casts = [
        'new' => 'array',
        'old' => 'array',
        'changes' => 'array'
    ];

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
