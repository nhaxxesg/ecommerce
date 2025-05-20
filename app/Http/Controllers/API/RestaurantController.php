<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="Restaurant",
 *     required={"name", "address", "schedule", "contact_info", "latitude", "longitude", "user_id"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string", maxLength=255),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="schedule", type="string"),
 *     @OA\Property(property="contact_info", type="string"),
 *     @OA\Property(property="latitude", type="number", format="float"),
 *     @OA\Property(property="longitude", type="number", format="float"),
 *     @OA\Property(property="user_id", type="integer", format="int64"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Tag(
 *     name="Restaurantes",
 *     description="Operaciones con restaurantes"
 * )
 */
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
     *         description="Lista de restaurantes con ubicación",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number"),
     *                 @OA\Property(property="longitude", type="number")
     *             )
     *         )
     *     )
     * )
     */
    public function map(): JsonResponse
    {
        $restaurants = Restaurant::select('id', 'name', 'latitude', 'longitude')->get();
        return response()->json($restaurants);
    }    /**
     * @OA\Get(
     *     path="/api/restaurants",
     *     summary="Listar todos los restaurantes",
     *     tags={"Restaurantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de restaurantes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="address", type="string"),
     *                 @OA\Property(property="schedule", type="string"),
     *                 @OA\Property(property="contact_info", type="string"),
     *                 @OA\Property(property="latitude", type="number"),
     *                 @OA\Property(property="longitude", type="number"),
     *                 @OA\Property(property="user_id", type="integer")
     *             )
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     **/
    public function index(): JsonResponse
    {
        // Obtener todos los restaurantes con su ubicación para el mapa
        $restaurants = Restaurant::all();
        return response()->json($restaurants);
    }

    /**
     * @OA\Post(
     *     path="/api/restaurants",
     *     summary="Crear un nuevo restaurante",
     *     tags={"Restaurantes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Restaurante creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
    **/
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
            'user_id' => 'required|exists:users,id',
        ]);

        // Asignar el usuario actual como propietario
        $restaurant = Restaurant::create($validated);
        
        return response()->json($restaurant, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/restaurants/{id}",
     *     summary="Obtener un restaurante específico",
     *     tags={"Restaurantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del restaurante",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del restaurante",
     *         @OA\JsonContent(ref="#/components/schemas/Restaurant")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurante no encontrado"
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
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
        $restaurant->delete();
        
        return response()->json(null, 204);
    }
}
