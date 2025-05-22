<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaypalController;

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

// Rutas de restaurantes
Route::apiResource('restaurants', RestaurantController::class);

// Rutas de menús
Route::apiResource('menus', MenuController::class);

// Rutas de pedidos
Route::get('orders', [OrderController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
Route::post('orders/{order}/deliver', [OrderController::class, 'markAsDelivered']);
Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);

// Rutas para paypal
Route::post('paypal/getAccessToken', [PaypalController::class, 'getAccessToken']);