<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\Web\RestaurantController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/menus/listar', function () {
    return Inertia::render('Menu/Listar');
})->middleware(['auth', 'verified'])->name('ListarMenus');

Route::get('/menus/create', function () {
    return Inertia::render('Menu/Crear');
})->middleware(['auth', 'verified'])->name('CrearMenus');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/imagenes/{id}', [ImagenController::class, 'index'])->name('imagenes.index');
Route::post('/imagenes/{id}', [ImagenController::class, 'store'])->name('imagenes.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/restaurantinfo', function () {
    return Inertia::render('RestaurantPage/index');
})->middleware(['auth', 'verified'])->name('restaurantinfo');

Route::get('/menudevolucion', function () {
    return Inertia::render('MenuDevoluciones/index');
})->name('menudevolucion');

require __DIR__.'/auth.php';
