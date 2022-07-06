<?php

namespace App\Http\Controllers\Pokemon;

use App\Http\Controllers\Controller;
use App\Models\Pokemon\Pokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $pokemon = Pokemon::query();

        if ($request->has('search')) {
            $pokemon->where('name', 'like', '%' . $request->input('search') . '%');
        }

        return $this->success($pokemon->paginate(24));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
    }

    /**
     * Display the specified resource.
     *
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function show(Pokemon $pokemon): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function update(Request $request, Pokemon $pokemon): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Pokemon $pokemon
     * @return JsonResponse
     */
    public function destroy(Pokemon $pokemon): JsonResponse
    {
        //
    }
}
