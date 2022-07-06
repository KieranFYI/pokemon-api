<?php

namespace App\Http\Controllers\Pokemon;

use App\Http\Controllers\Controller;
use App\Models\Pokemon\Pokemon;
use App\Models\Pokemon\PokemonType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * @group Pokemon
 */
class PokemonController extends Controller
{
    /**
     * Display a listing of the pokemon.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pokemon = Pokemon::with('typeOne', 'typeTwo');

        $validated = $request->validate([
            'search' => ['nullable'],
            'type_one' => ['nullable', 'array'],
            'type_one.*' => ['numeric', 'integer'],
            'type_two' => ['nullable', 'array'],
            'type_two.*' => ['numeric', 'integer'],
            'hit_points' => ['nullable', 'array', 'min:2', 'max:2'],
            'hit_points.*' => ['numeric', 'integer'],
            'attack' => ['nullable', 'array', 'min:2', 'max:2'],
            'attack.*' => ['numeric', 'integer'],
            'defense' => ['nullable', 'array', 'min:2', 'max:2'],
            'defense.*' => ['numeric', 'integer'],
            'speed' => ['nullable', 'array', 'min:2', 'max:2'],
            'speed.*' => ['numeric', 'integer'],
            'special' => ['nullable', 'array', 'min:2', 'max:2'],
            'special.*' => ['numeric', 'integer'],
        ]);

        if (!empty($validated['search'])) {
            $pokemon->where('name', 'like', '%' . $validated['search'] . '%');
        }

        if (!empty($validated['type_one'])) {
            $pokemon->whereIn('pokemon_type_one_id', $validated['type_one']);
        }

        if (!empty($validated['type_two'])) {
            $pokemon->where('pokemon_type_two_id', $validated['type_two']);
        }

        $ranges = ['hit_points', 'attack', 'defense', 'speed', 'special'];
        foreach ($ranges as $key) {
            if (empty($validated[$key])) {
                continue;
            }
            $pokemon->whereBetween($key, $validated[$key]);
        }

        return $this->success($pokemon->paginate(24));
    }

    /**
     * Return a list of min and max values for filtering
     *
     * @return JsonResponse
     */
    public function filters(): JsonResponse
    {
        $aggregates = Pokemon::query()->select([
            DB::raw('MIN(hit_points) as min_hit_points'),
            DB::raw('MAX(hit_points) as max_hit_points'),
            DB::raw('MIN(attack) as min_attack'),
            DB::raw('MAX(attack) as max_attack'),
            DB::raw('MIN(defense) as min_defense'),
            DB::raw('MAX(defense) as max_defense'),
            DB::raw('MIN(speed) as min_speed'),
            DB::raw('MAX(speed) as max_speed'),
            DB::raw('MIN(special) as min_special'),
            DB::raw('MAX(special) as max_special'),
        ])->first();

        return $this->success([
            'hit_points' => [
                'name' => 'Hitpoints',
                'min' => $aggregates->min_hit_points,
                'max' => $aggregates->max_hit_points,
            ],
            'attack' => [
                'name' => 'Attack',
                'min' => $aggregates->min_attack,
                'max' => $aggregates->max_attack,
            ],
            'defense' => [
                'name' => 'Defense',
                'min' => $aggregates->min_defense,
                'max' => $aggregates->max_defense,
            ],
            'speed' => [
                'name' => 'Speed',
                'min' => $aggregates->min_speed,
                'max' => $aggregates->max_speed,
            ],
            'special' => [
                'name' => 'Special',
                'min' => $aggregates->min_special,
                'max' => $aggregates->max_special,
            ],
        ]);
    }

    /**
     * Store a newly created pokemon in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', Rule::unique(Pokemon::class)],
            'description' => ['required'],
            'type_one_id' => ['required', 'numeric', 'integer', Rule::exists(PokemonType::class, 'id')],
            'type_two_id' => ['nullable', 'numeric', 'integer', Rule::exists(PokemonType::class, 'id')],
            'hit_points' => ['required', 'numeric', 'integer'],
            'attack' => ['required', 'numeric', 'integer'],
            'defense' => ['required', 'numeric', 'integer'],
            'speed' => ['required', 'numeric', 'integer'],
            'special' => ['required', 'numeric', 'integer'],
            'image_url_gif' => ['nullable', 'url'],
            'image_url_png' => ['nullable', 'url'],
        ]);
        $validated['pokemon_type_one_id'] = $validated['type_one_id'];
        unset($validated['type_one_id']);
        $validated['pokemon_type_two_id'] = $validated['type_two_id'] ?? null;
        unset($validated['type_two_id']);

        $pokemon = Pokemon::create($validated);

        return $this->success($pokemon);
    }

    /**
     * Display the specified pokemon.
     *
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function show(Pokemon $pokemon): JsonResponse
    {
        return $this->success($pokemon);
    }

    /**
     * Update the specified pokemon in storage.
     *
     * @param Request $request
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function update(Request $request, Pokemon $pokemon): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', Rule::unique(Pokemon::class)],
            'description' => ['nullable'],
            'type_one_id' => ['nullable', 'numeric', 'integer', Rule::exists(PokemonType::class, 'id')],
            'type_two_id' => ['nullable', 'numeric', 'integer', Rule::exists(PokemonType::class, 'id')],
            'hit_points' => ['nullable', 'numeric', 'integer'],
            'attack' => ['nullable', 'numeric', 'integer'],
            'defense' => ['nullable', 'numeric', 'integer'],
            'speed' => ['nullable', 'numeric', 'integer'],
            'special' => ['nullable', 'numeric', 'integer'],
            'image_url_gif' => ['nullable', 'url'],
            'image_url_png' => ['nullable', 'url'],
        ]);
        if (!empty($validated['type_one_id'])) {
            $validated['pokemon_type_one_id'] = $validated['type_one_id'];
        }
        unset($validated['type_one_id']);
        if (!empty($validated['type_one_id'])) {
            $validated['pokemon_type_two_id'] = $validated['type_two_id'] ?? null;
        }
        unset($validated['type_two_id']);

        $pokemon->update($validated);

        return $this->success($validated);
    }

    /**
     * Remove the specified pokemon from storage.
     *
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function destroy(Pokemon $pokemon): JsonResponse
    {
        $pokemon->delete();

        return $this->success(message: $pokemon->name . ' has been deleted');
    }
}
