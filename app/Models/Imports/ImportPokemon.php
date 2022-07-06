<?php

namespace App\Models\Imports;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportPokemon extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['filename', 'filename_original', 'finished_at'];

    /**
     * @var string[]
     */
    protected $casts = [
        'finished_at' => 'datetime'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
