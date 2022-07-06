<?php

namespace App\Models\Pokemon;

use App\Traits\Observable;
use Illuminate\Database\Eloquent\Model;

class PokemonType extends Model
{
    use Observable;

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @var string[]
     */
    protected $hidden = ['created_at', 'updated_at'];
}
