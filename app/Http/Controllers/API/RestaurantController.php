<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        // Obtener todos los restaurantes con su ubicación para el mapa
        $restaurants = Restaurant::all();
        return response()->json($restaurants);
    }

    public function store(Request $request): JsonResponse
    {
        // Validar los datos del restaurante
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'schedule' => 'required|string',
            'contact_info' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Asignar el usuario actual como propietario
        $validated['user_id'] = auth()->id();
        
        // Crear el restaurante
        $restaurant = Restaurant::create($validated);
        
        return response()->json($restaurant, 201);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        // Cargar los menús del día actual
        $restaurant->load(['menus' => function($query) {
            $query->where('available_date', today())
                  ->where('is_available', true);
        }]);
        
        return response()->json($restaurant);
    }

    public function update(Request $request, Restaurant $restaurant): JsonResponse
    {
        // Verificar si el usuario es el propietario
        if ($restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validar y actualizar
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'schedule' => 'sometimes|string',
            'contact_info' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
        ]);

        $restaurant->update($validated);
        
        return response()->json($restaurant);
    }

    public function destroy(Restaurant $restaurant): JsonResponse
    {
        // Verificar si el usuario es el propietario
        if ($restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $restaurant->delete();
        
        return response()->json(null, 204);
    }
}
