<?php

namespace App\Models\Pokemon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pokemon extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'description',
        'hit_points', 'attack', 'defense', 'speed', 'special',
        'image_url_gif', 'image_url_png'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'hit_points' => 'integer',
        'attack' => 'integer',
        'defense' => 'integer',
        'speed' => 'integer',
        'special' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function typeOne(): BelongsTo
    {
        return $this->belongsTo(PokemonType::class, 'pokemon_type_one_id');
    }

    /**
     * @return BelongsTo
     */
    public function typeTwo(): BelongsTo
    {
        return $this->belongsTo(PokemonType::class, 'pokemon_type_two_id');
    }
}
