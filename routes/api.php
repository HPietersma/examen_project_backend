<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\FamilyController;
use App\HTTP\Controllers\ProductController;
use App\HTTP\Controllers\CategoryController;
use App\HTTP\Controllers\SupplierController;

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



// VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT WANNEER ER INGELOGD IS
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'authToken']);



    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS DIRECTIE
    Route::group(['middleware' => ['isDirectie']], function() {
        Route::get('parcels', [parcelController::class, 'parcels']);
        route::apiResource('klanten', FamilyController::class);




    });

    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS MAGAZIJNMEDEWERKER OF DIRECTIE
    Route::group(['middleware' => ['isMagazijnmedewerker']], function() {
        route::apiResource('products', ProductController::class);
        route::apiResource('categories', CategoryController::class);
        route::apiResource('suppliers', SupplierController::class);


    });

    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS VRIJWILLIGER OF DIRECTIE
    Route::group(['middleware' => ['isVrijwilliger']], function() {




    });




});

