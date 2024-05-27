<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MetController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\AbonneController;
use App\Http\Controllers\API\EtappeController;
use App\Http\Controllers\API\RecetteController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\IngredientController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

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


Route::post('login',[AccessTokenController::class,'issueToken'])

->middleware(['api-login','throttle']);

Route::post('register', [RegisterController::class, 'register']);

// Route::post('login', [RegisterController::class, 'login']);



Route::middleware(['api-login','throttle'])->group( function () {
    
    Route::apiResource('recettes', RecetteController::class);
    
    Route::apiResource('mets', MetController::class);
    
    Route::apiResource('abonnes', AbonneController::class);

    Route::apiResource('ingredients',IngredientController::class);

    Route::apiResource('etapes',EtappeController::class);

    // Route::get('/boque/{abonne}',[AbonnementController::class,'blockUser']);
    
    Route::get('get-user', [RegisterController::class, 'userInfo']);

    Route::get('/convertir', [VideoController::class, 'stocker']);

    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/operation-compte/{id}',[AbonneController::class, 'bloquer']);
});
