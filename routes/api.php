<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\HTTP\Controllers\AuthController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);



// VOLGENDE ROUTES KUNNEN ALLEEN WORDEMN BENADERT WANNEER ER INGELOGD IS 
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('logout', [AuthController::class, 'logout']);



    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS DIRECTIE
    Route::group(['middleware' => ['isDirectie']], function() {
    



    });

    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS MAGAZIJNMEDEWERKER OF HOGER
    Route::group(['middleware' => ['isMagazijnmedewerker']], function() {
    



    });

    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS VRIJWILLIGER OF HOGER
    Route::group(['middleware' => ['isVrijwilliger']], function() {
    



    });




});

