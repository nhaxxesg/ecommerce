<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Obtener lista de pedidos",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos según el rol del usuario"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Si es propietario, mostrar pedidos de sus restaurantes
        if ($user->role === 'propietario') {
            $orders = Order::whereHas('restaurant', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['items.menu', 'customer'])->get();
        } else {
            // Si es cliente, mostrar sus pedidos
            $orders = Order::where('user_id', $user->id)
                         ->with(['items.menu', 'restaurant'])
                         ->get();
        }
        
        return response()->json($orders);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Crear un nuevo pedido",
     *     tags={"Pedidos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "restaurant_id", "items"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="restaurant_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="menu_id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            return DB::transaction(function() use ($validated) {
                // Crear el pedido
                $order = Order::create([
                    'user_id' => $validated['user_id'],
                    'restaurant_id' => $validated['restaurant_id'],
                    'status' => 'pendiente',
                    'total_amount' => 0,
                    'order_number' => Str::random(10)
                ]);

                $total = 0;

                // Crear los items del pedido
                foreach ($validated['items'] as $item) {
                    $menu = Menu::findOrFail($item['menu_id']);
                    $subtotal = $menu->price * $item['quantity'];
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $menu->price,
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                }

                // Actualizar el total del pedido
                $order->update(['total_amount' => $total]);

                return response()->json($order->load('items.menu'), 201);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar el pedido'], 500);
        }
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Verificar si el usuario tiene acceso al pedido
        if (!$this->userCanAccessOrder($order, $request->user_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load(['items.menu', 'restaurant']));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);
        
        // Solo el propietario del restaurante puede actualizar el estado
        if ($order->restaurant->user_id !== $user->id || $user->role !== 'propietario') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendiente,en preparación,listo para entrega,entregado'
        ]);

        $order->update($validated);
        
        return response()->json($order);
    }

    private function userCanAccessOrder(Order $order, int $userId): bool
    {
        $user = User::find($userId);
        return $userId === $order->user_id || 
               ($user->role === 'propietario' && $userId === $order->restaurant->user_id);
    }
}
