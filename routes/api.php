<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::post('login', [LoginController::class, 'attemptLogin']);
Route::post('register', [RegisterController::class, 'register']);

Route::group(["prefix" => "contact", "middleware" => "auth:api"], function () {
    Route::post('list', [ContactController::class, 'index']);
    Route::post('list/{id}', [ContactController::class, 'indexForId']);
    Route::post('storeOrUpdateContact', [ContactController::class, 'storeOrUpdateContact']);
    Route::post('delete/{id}', [ContactController::class, 'destroy']);
});
