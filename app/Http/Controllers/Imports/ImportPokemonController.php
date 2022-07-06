<?php

namespace App\Http\Controllers\Imports;

use App\Http\Controllers\Controller;
use App\Imports\PokemonImport;
use App\Jobs\Imports\ImportPokemonJob;
use App\Models\Imports\ImportPokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportPokemonController extends Controller
{
    /**
     * Display a listing of the pokemon imports.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success([
            'imports' => ImportPokemon::orderByDesc('id')
                ->limit(10)
                ->get(),
        ]);
    }

    /**
     * Store a newly created pokemon import in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file']
        ]);

        DB::transaction(function () use ($validated) {
            /** @var UploadedFile $file */
            $file = $validated['file'];
            $filename = Str::uuid() . '.csv';

            $import = new ImportPokemon([
                'filename' => 'imports/pokemon/' . $filename,
                'filename_original' => $file->getClientOriginalName()
            ]);
            $import->user()->associate(Auth::user());
            $import->save();

            $file->storeAs('imports/pokemon', $filename);
            ImportPokemonJob::dispatch($import);
        });

        return $this->success(message: 'File is being imported');
    }
}
