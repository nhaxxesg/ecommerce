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

// Ruta de prueba
Route::get('/test', function () {
    return ['message' => 'API is working'];
});

// Rutas públicas
Route::get('restaurants/map', [RestaurantController::class, 'map']); // RF-7: Mostrar mapa de restaurantes
Route::get('menus/search', [MenuController::class, 'search']); // RF-3: Búsqueda de menús

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de restaurantes (RF-8)
    Route::apiResource('restaurants', RestaurantController::class);
    
    // Rutas de menús (RF-2)
    Route::apiResource('menus', MenuController::class);
    
    // Rutas de pedidos (RF-4, RF-5, RF-6)
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    
    // Ruta para actualizar estado del pedido (RF-6)
    Route::post('orders/{order}/deliver', [OrderController::class, 'markAsDelivered']);
});
