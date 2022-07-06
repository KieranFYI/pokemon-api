<?php

namespace App\Jobs\Imports;

use App\Imports\PokemonImport;
use App\Models\Imports\ImportPokemon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportPokemonJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var ImportPokemon
     */
    private ImportPokemon $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImportPokemon $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new PokemonImport())->import($this->import->filename);
        $this->import->update([
            'finished_at' => now(),
        ]);
    }
}
