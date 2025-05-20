<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas de autenticación
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Ruta de prueba
Route::get('/test', function () {
    return ['message' => 'API is working'];
});

// Rutas públicas
Route::get('restaurants/map', [RestaurantController::class, 'map']); // RF-7: Mostrar mapa de restaurantes
Route::get('menus/search', [MenuController::class, 'search']); // RF-3: Búsqueda de menús

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Ruta de logout
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Rutas de restaurantes (RF-8) - Solo propietarios pueden gestionar restaurantes
    Route::middleware('role:propietario')->group(function () {
        Route::apiResource('restaurants', RestaurantController::class)->except(['index', 'show']);
    });
    Route::apiResource('restaurants', RestaurantController::class)->only(['index', 'show']);
    
    // Rutas de menús (RF-2) - Solo propietarios pueden gestionar menús
    Route::middleware('role:propietario')->group(function () {
        Route::apiResource('menus', MenuController::class)->except(['index', 'show']);
    });
    Route::apiResource('menus', MenuController::class)->only(['index', 'show']);
    
    // Rutas de pedidos (RF-4, RF-5, RF-6)
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    
    // Ruta para actualizar estado del pedido (RF-6)
    Route::post('orders/{order}/deliver', [OrderController::class, 'markAsDelivered']);
});
