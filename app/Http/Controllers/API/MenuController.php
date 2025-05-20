<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use App\Models\Restaurant;
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
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'available_date' => 'required|date',
            'food_type' => 'required|string',
        ]);

        // Verificar que el usuario es propietario del restaurante
        $user = User::findOrFail($request->user_id);
        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        
        if ($restaurant->user_id !== $user->id || $user->role !== 'propietario') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu = Menu::create($validated);
        
        return response()->json($menu, 201);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        // Validar los datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'image_url' => 'nullable|url',
            'available_date' => 'sometimes|date',
            'food_type' => 'sometimes|string',
            'is_available' => 'sometimes|boolean',
        ]);

        // Verificar que el usuario es propietario del restaurante
        $user = User::findOrFail($request->user_id);
        
        if ($menu->restaurant->user_id !== $user->id || $user->role !== 'propietario') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu->update($validated);
        
        return response()->json($menu);
    }

    public function destroy(Request $request, Menu $menu): JsonResponse
    {
        // Verificar que el usuario es propietario del restaurante
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        
        if ($menu->restaurant->user_id !== $user->id || $user->role !== 'propietario') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu->delete();
        
        return response()->json(null, 204);
    }
}
