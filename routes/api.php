<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RecetteController;

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

Route::apiResource("recette", RecetteController::class);
Route::get('dashbord/create',[RecetteController::class,'create'])->name('recette.create');
Route::get('dashbord/statistique',[RecetteController::class,'create'])->name('recette.dashboard');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
