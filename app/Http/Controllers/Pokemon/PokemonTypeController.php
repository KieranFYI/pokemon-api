<?php

namespace App\Http\Controllers\Pokemon;

use App\Http\Controllers\Controller;
use App\Models\Pokemon\PokemonType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Pokemon Type
 */
class PokemonTypeController extends Controller
{
    /**
     * Display a listing of the pokemon type.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(PokemonType::orderBy('name', 'asc')->get());
    }

    /**
     * Store a newly created pokemon type in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique(PokemonType::class),
            ]
        ]);

        $pokemonType = PokemonType::create($validated);

        return $this->success($pokemonType);
    }

    /**
     * Display the specified pokemon type.
     *
     * @param PokemonType $type
     * @return JsonResponse
     */
    public function show(PokemonType $type): JsonResponse
    {
        return $this->success($type);
    }

    /**
     * Update the specified pokemon type in storage.
     *
     * @param Request $request
     * @param PokemonType $type
     * @return JsonResponse
     */
    public function update(Request $request, PokemonType $type): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique(PokemonType::class)->ignore($type->id),
            ]
        ]);

        $type->update($validated);

        return $this->success($type);
    }

    /**
     * Remove the specified pokemon type from storage.
     *
     * @param PokemonType $type
     * @return JsonResponse
     */
    public function destroy(PokemonType $type): JsonResponse
    {
        $type->delete();

        return $this->success(message: $type->name . ' has been deleted');
    }
}
