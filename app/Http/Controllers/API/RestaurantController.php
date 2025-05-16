<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Restaurantes",
 *     description="API Endpoints de restaurantes"
 * )
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de Restaurantes",
 *     version="1.0.0",
 *     description="API para sistema de pedidos de comida rápida"
 * )
 * @OA\Server(
 *     description="Servidor Local",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 * @OA\Tag(
 *     name="Restaurantes",
 *     description="Endpoints de gestión de restaurantes"
 * )
 */
class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/restaurants/map",
     *     summary="Obtener restaurantes para el mapa",
     *     tags={"Restaurantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de restaurantes con ubicación"
     *     )
     * )
     */
    public function map(): JsonResponse
    {
        $restaurants = Restaurant::select('id', 'name', 'latitude', 'longitude')->get();
        return response()->json($restaurants);
    }

    /**
     * @OA\Get(
     *     path="/api/restaurants",
     *     summary="Listar restaurantes",
     *     tags={"Restaurantes"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de restaurantes"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        // Obtener todos los restaurantes con su ubicación para el mapa
        $restaurants = Restaurant::all();
        return response()->json($restaurants);
    }

    /**
     * @OA\Post(
     *     path="/api/restaurants",
     *     summary="Crear un restaurante",
     *     tags={"Restaurantes"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Restaurante creado"
     *     )
     * )
     */
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
