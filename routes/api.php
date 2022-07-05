<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegistrationController;
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
    ->middleware('throttle:auth')
    ->group(function () {
        Route::post('register', [RegistrationController::class, 'register'])
            ->name('authentication.register');
        Route::post('login', [LoginController::class, 'login'])
            ->name('authentication.login');
        Route::post('logout', [LoginController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('authentication.logout');
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});
