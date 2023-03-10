<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\FamilyController;
use App\Http\Controllers\ParcelController;
use App\HTTP\Controllers\ProductController;
use App\HTTP\Controllers\CategoryController;
use App\HTTP\Controllers\SupplierController;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\RoleController;

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
    Route::apiResource('roles', RoleController::class);
    Route::get('restoreKlant/{id}', [FamilyController::class, 'restore']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::apiResource('categories', CategoryController::class);

    
    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS DIRECTIE
    Route::group(['middleware' => ['isDirectie']], function() {
        Route::apiResource('users', UserController::class);
        Route::get('restoreUser/{id}', [UserController::class, 'restore']);
        Route::apiResource('klanten', FamilyController::class);
    });


    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS MAGAZIJNMEDEWERKER OF DIRECTIE
    Route::group(['middleware' => ['isMagazijnmedewerker']], function() {
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::post('products', [ProductController::class, 'store']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        Route::apiResource('suppliers', SupplierController::class);
        Route::get('suppliersWithProducts', [SupplierController::class, 'suppliersWithProducts']);
        Route::get('supplierWithProducts/{id}', [SupplierController::class, 'supplierWithProducts']);
    });


    // VOLGENDE ROUTES KUNNEN ALLEEN WORDEN BENADERT ALS VRIJWILLIGER OF DIRECTIE
    Route::group(['middleware' => ['isVrijwilliger']], function() {
        Route::get('familiesWithoutParcel', [FamilyController::class, 'familiesWithoutParcel']);
        route::apiResource('parcels', ParcelController::class);
    });
});

