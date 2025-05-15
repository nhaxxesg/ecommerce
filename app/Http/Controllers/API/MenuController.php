<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Filtrar menús por restaurante, tipo de comida y precio
        $query = Menu::query()
            ->where('available_date', today())
            ->where('is_available', true);

        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        if ($request->has('food_type')) {
            $query->where('food_type', $request->food_type);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $menus = $query->with('restaurant')->get();
        
        return response()->json($menus);
    }

    public function store(Request $request): JsonResponse
    {
        // Validar los datos del menú
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'available_date' => 'required|date',
            'food_type' => 'required|string',
        ]);

        // Verificar que el usuario es propietario del restaurante
        $restaurant = \App\Models\Restaurant::find($validated['restaurant_id']);
        if ($restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu = Menu::create($validated);
        
        return response()->json($menu, 201);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        // Verificar que el usuario es propietario del restaurante
        if ($menu->restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'image_url' => 'nullable|url',
            'available_date' => 'sometimes|date',
            'food_type' => 'sometimes|string',
            'is_available' => 'sometimes|boolean',
        ]);

        $menu->update($validated);
        
        return response()->json($menu);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        // Verificar que el usuario es propietario del restaurante
        if ($menu->restaurant->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu->delete();
        
        return response()->json(null, 204);
    }
}
