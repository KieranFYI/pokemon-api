<?php

use App\Http\Controllers\Authentication\APITokenController;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegistrationController;
use App\Http\Controllers\Imports\ImportPokemonController;
use App\Http\Controllers\Pokemon\PokemonController;
use App\Http\Controllers\Pokemon\PokemonTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('auth')
    ->middleware(['throttle:auth', 'stateful'])
    ->group(function () {
        Route::post('register', [RegistrationController::class, 'register'])
            ->name('authentication.register');
        Route::post('login', [LoginController::class, 'login'])
            ->name('authentication.login');
        Route::post('logout', [LoginController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('authentication.logout');
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::get('user', function (Request $request) {
            return $request->user();
        });

        Route::post('auth/token', [APITokenController::class, 'store'])
            ->name('authentication.token');

        Route::resource('pokemon/type', PokemonTypeController::class, ['except' => ['create', 'edit']]);

        Route::get('pokemon/filters', [PokemonController::class, 'filters'])
            ->name('pokemon.filters');
        Route::resource('pokemon', PokemonController::class, ['except' => ['create', 'edit']]);

        Route::prefix('imports')
            ->group(function () {
                Route::get('pokemon', [ImportPokemonController::class, 'index'])
                    ->name('import.pokemon.index');
                Route::post('pokemon', [ImportPokemonController::class, 'store'])
                    ->name('import.pokemon.store');
            });
    });
