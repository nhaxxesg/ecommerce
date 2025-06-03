<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RestaurantController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\PaymentController;

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
Route::apiResource('orders', OrderController::class);

// Rutas para paypal
Route::post('paypal/getAccessToken', [PaypalController::class, 'getAccessToken']);

// Rutas de pagos
Route::apiResource('payments', PaymentController::class);

Route::post('/orders/completar-pago', [OrderController::class, 'completarPago']);