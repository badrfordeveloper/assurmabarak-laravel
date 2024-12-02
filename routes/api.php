<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', [TestController::class,"test"]);
Route::post('/tarificateur', [EcaController::class,"tarificateur"]);
Route::get('/getDependecies/{nbrPiece}', [EcaController::class,"getDependecies"]);
Route::post('/save', [EcaController::class,"saveContrat"]);
Route::post('/not-eligible', [EcaController::class,"notEligible"]);
Route::post('/send-email', [ContactController::class, 'sendEmail']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
