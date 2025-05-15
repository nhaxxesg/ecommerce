<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de restaurantes
    Route::apiResource('restaurants', RestaurantController::class);
    
    // Rutas de menús
    Route::apiResource('menus', MenuController::class);
    
    // Rutas de pedidos
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
});
